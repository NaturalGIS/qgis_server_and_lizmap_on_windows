__copyright__ = 'Copyright 2021, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'

import binascii

from enum import Enum, auto
from functools import lru_cache
from typing import Tuple, Union

from qgis.core import (
    QgsCoordinateReferenceSystem,
    QgsCoordinateTransform,
    QgsDataSourceUri,
    QgsFeatureRequest,
    QgsGeometry,
    QgsProject,
    QgsProviderConnectionException,
    QgsProviderRegistry,
    QgsSpatialIndex,
    QgsVectorLayer,
)
from qgis.PyQt.QtCore import QVariant

from lizmap_server.logger import Logger, profiling
from lizmap_server.tools import to_bool

# TODO implement LRU cache with this variable
CACHE_MAX_SIZE = 100

# 1 = 0 results in a "false" in OGR/PostGIS
# ET : I didn't find a proper false value in OGR
NO_FEATURES = '1 = 0'
ALL_FEATURES = ''


# noinspection PyArgumentList
class FilterType(Enum):
    QgisExpression = auto()
    PlainSqlQuery = auto()
    SafeSqlQuery = auto()


class FilterByPolygon:

    def __init__(
            self, config: dict, layer: QgsVectorLayer, editing: bool = False,
            filter_type: FilterType = FilterType.PlainSqlQuery,
    ):
        """Constructor for the filter by polygon.

        :param config: The filter by polygon configuration as dictionary
        :param layer: The vector layer to filter
        :param editing: If the filter must be used only for editing
        :param filter_type: If we generate a QGIS expression or a plain SQL with spatial relationship or not.
        """
        self.connection = None
        # QGIS Server can consider the ST_Intersect/ST_Contains not safe regarding SQL injection.
        # Using this flag will transform or not the ST_Intersect/ST_Contains into an IN by making the query
        # straight to PostGIS.
        self.filter_type = filter_type
        self.config = config
        self.editing = editing
        # noinspection PyArgumentList
        self.project = QgsProject.instance()

        # Current layer in the request
        self.layer = layer

        # Will be filled if the current layer is filtered
        self.primary_key = None
        self.filter_mode = None
        self.spatial_relationship = None
        self.use_centroid = None

        # Will be filled with the polygon layer
        self.polygon = None
        self.group_field = None
        self.filter_by_user = False

        # Read the configuration
        self._parse()

    def is_filtered(self) -> bool:
        """If the configuration is filtering the given layer."""
        return self.primary_key is not None

    def is_filtered_by_user(self) -> bool:
        """If the filter by polygon is configured with the "filter by user" flag
        we must filter by user and not by groups
        """
        return self.filter_by_user

    def _parse(self) -> None:
        """Read the configuration and fill variables"""
        # Leave as quick as possible
        if not self.layer.isSpatial():
            return None

        if self.config is None:
            return None

        layers = self.config.get('layers')
        if not layers:
            return None

        for layer in layers:
            if layer.get("layer") == self.layer.id():
                self.primary_key = layer.get('primary_key')
                self.filter_mode = layer.get('filter_mode')
                self.spatial_relationship = layer.get('spatial_relationship')
                self.use_centroid = layer.get('use_centroid', False)
                break

        if self.primary_key is None:
            return None

        config = self.config.get("config")
        self.polygon = self.project.mapLayer(config['polygon_layer_id'])
        self.group_field = config.get("group_field")

        # Filter by groups or by user (check the "filter_by_user" flag)
        self.filter_by_user = to_bool(config.get('filter_by_user'))

    def is_valid(self) -> bool:
        """ If the configuration is valid or not."""
        if not self.polygon:
            Logger.critical("The polygon layer for filtering is not valid.")
            return False

        if not self.polygon and self.polygon.isValid():
            Logger.critical("The polygon layer for filtering is not valid.")
            return False

        if self.polygon.fields().indexOf(self.group_field) < 0:
            Logger.critical(
                "The field {} used for filtering does not exist in {}".format(
                    self.group_field, self.polygon.name()))
            return False

        if not self.layer.isValid():
            Logger.critical(
                "The field {} used for filtering does not exist in {}".format(
                    self.primary_key, self.layer.name()))
            return False

        if self.layer.fields().indexOf(self.primary_key) < 0:
            Logger.critical(
                "The field {} used for filtering does not exist in {}".format(
                    self.primary_key, self.layer.name()))

        return True

    def sql_query(self, uri: QgsDataSourceUri, sql) -> Tuple[Tuple]:
        """ For a given URI, execute an SQL query and return the result. """
        if self.connection is None:
            # noinspection PyArgumentList
            metadata = QgsProviderRegistry.instance().providerMetadata('postgres')
            self.connection = metadata.createConnection(uri.uri(), {})
            try:
                self.connection.executeSql("SET application_name='QGIS Lizmap Server : Filter By Polygon';")
            except QgsProviderConnectionException as e:
                Logger.log_exception(e)

        results = self.connection.executeSql(sql)
        return results

    @profiling
    def subset_sql(self, groups_or_user: tuple) -> Tuple[str, str]:
        """ Get the SQL subset string for the current groups of the user or the user.

        :param groups_or_user: List of groups or users belongings to the user
                               or the user if we need to filter by user.
        :returns: The subset SQL string to use
        """
        # Disabled, sometimes featureCount is expansive it seems ?
        # if self.layer.featureCount() == 0:
        #     # Layer is empty, let's go faster ...
        #     Logger.info(
        #         "Layer {} is empty, returning default NO_FEATURES {}".format(self.layer.name(), NO_FEATURES))
        #     return NO_FEATURES, ''

        if self.filter_mode == 'editing':
            if not self.editing:
                Logger.info(
                    "Layer is editing only BUT we are not in an editing session. Return all features.")
                return ALL_FEATURES, ''

            Logger.info(
                "Layer is editing only AND we are in an editing session. Continue to find the subset string")

        # We need to have a cache for this, valid for the combo polygon layer id & user_groups
        # as it will be done for each WMS or WFS query
        if self.polygon.providerType() == 'postgres':
            polygon = self._polygon_for_groups_with_sql_query(groups_or_user)
        else:
            polygon = self._polygon_for_groups_with_qgis_api(groups_or_user)
        # Logger.info("LRU Cache _polygon_for_groups : {}".format(self._polygon_for_groups.cache_info()))

        if polygon.isEmpty():
            Logger.info("The polygon is empty, returning default NO_FEATURES {}".format(NO_FEATURES))
            # Let's try to free the connection
            self.connection = None
            return NO_FEATURES, ''

        ewkt = "SRID={crs};{wkt}".format(
            crs=self.polygon.crs().postgisSrid(),
            wkt=polygon.asWkt(6 if self.polygon.crs().isGeographic() else 2)
        )

        use_st_intersect = False if self.spatial_relationship == 'contains' else True

        if self.filter_type == FilterType.QgisExpression:
            qgis_expression = self._format_qgis_expression_relationship(
                self.layer.sourceCrs(),
                self.polygon.sourceCrs(),
                polygon,
                use_st_intersect,
                self.use_centroid,
            )
            return qgis_expression, ewkt

        if self.layer.providerType() == 'postgres':
            uri = QgsDataSourceUri(self.layer.source())
            st_relation = self._format_sql_st_relationship(
                self.layer.sourceCrs(),
                self.polygon.sourceCrs(),
                uri.geometryColumn(),
                polygon,
                use_st_intersect,
                self.use_centroid,
            )

            # If we can use the complexe query with ST_Intersects or ST_Contains
            # as a subset string for a layer in QGIS Server.
            # This is not allowed yet to protect from SQL injections.
            if self.filter_type == FilterType.PlainSqlQuery:
                return st_relation, ewkt

            # Build the filter with a list of IDS based on a SQL query
            # using the spatial relationship WHERE clause.
            result = self._features_ids_with_sql_query(st_relation), ewkt

            # Let's try to free the connection
            self.connection = None
            return result

        # Still here ? So we use the slow method with QGIS API
        subset = self._features_ids_with_qgis_api(polygon)
        # Logger.info("LRU Cache _layer_not_postgres : {}".format(self._layer_not_postgres.cache_info()))
        return subset, ewkt

    @profiling
    @lru_cache(maxsize=CACHE_MAX_SIZE)
    def _polygon_for_groups_with_qgis_api(self, groups_or_user: tuple) -> QgsGeometry:
        """ All features from the polygon layer corresponding to the user groups or the user """
        expression = """
array_intersect(
    array_foreach(
        string_to_array("{polygon_field}"),
        trim(@element)
    ),
    array_foreach(
        string_to_array('{groups_or_user}'),
        trim(@element)
    )
)""".format(
            polygon_field=self.group_field,
            groups_or_user=','.join(groups_or_user)
        )

        # Create request
        request = QgsFeatureRequest()
        request.setSubsetOfAttributes([])
        request.setFilterExpression(expression)

        polygon_geoms = []
        for feature in self.polygon.getFeatures(request):
            polygon_geoms.append(feature.geometry())

        return QgsGeometry().collectGeometry(polygon_geoms)

    @profiling
    @lru_cache(maxsize=CACHE_MAX_SIZE)
    def _polygon_for_groups_with_sql_query(self, groups_or_user: tuple) -> QgsGeometry:
        """ All features from the polygon layer corresponding to the user groups
        or the user for a PostgreSQL layer.

        Only for QGIS >= 3.10
        """
        uri = QgsDataSourceUri(self.polygon.source())
        try:
            sql = r"""
WITH current_groups AS (
    SELECT
        ARRAY_REMOVE(
            STRING_TO_ARRAY(
                regexp_replace(
                    '{groups_or_user}', '[^a-zA-Z0-9_-]', ',', 'g'
                ),
                ','
            ),
        '') AS user_group
)
SELECT
        1 AS id, ST_AsBinary(ST_Union("{geom}")) AS geom
FROM
        "{schema}"."{table}" AS p,
        current_groups AS c
WHERE
c.user_group && (
    ARRAY_REMOVE(
        STRING_TO_ARRAY(
            regexp_replace(
                "{polygon_field}", '[^a-zA-Z0-9_-]', ',', 'g'
            ),
            ','
        ),
    '')
)



""".format(
                polygon_field=self.group_field,
                groups_or_user=','.join(groups_or_user),
                geom=uri.geometryColumn(),
                schema=uri.schema(),
                table=uri.table(),
            )
            Logger.info(
                "Requesting the database about polygons for the current groups or user with : \n{}".format(sql))

            results = self.sql_query(uri, sql)
            wkb = results[0][1]

            geom = QgsGeometry()
            if isinstance(wkb, QVariant) and wkb.isNull():
                return geom

            # Remove \x from string
            # Related to https://gis.stackexchange.com/questions/411545/use-st-asbinary-from-postgis-in-pyqgis
            wkb = wkb[2:]
            geom.fromWkb(binascii.unhexlify(wkb))
            return geom
        except Exception as e:
            # Let's be safe
            Logger.log_exception(e)
            Logger.critical(
                "The filter_by_polygon._polygon_for_groups_with_sql_query failed when requesting PostGIS.\n"
                "Using the QGIS API")
            return self._polygon_for_groups_with_qgis_api(groups_or_user)

    @profiling
    @lru_cache(maxsize=CACHE_MAX_SIZE)
    def _features_ids_with_qgis_api(self, polygons: QgsGeometry) -> str:
        """ List all features using the QGIS API.

        :returns: The subset SQL string.
        """
        # For other types, we need to find all the ids with an expression
        # And then search for these ids in the substring, as it must be SQL

        # Build the spatial index
        index = QgsSpatialIndex()
        Logger.info(
            "Building index on {} having CRS {}. The CRS of the polygon is {}".format(
                self.layer.name(),
                self.layer.crs().authid(),
                self.polygon.crs().authid(),
            ))
        index.addFeatures(self.layer.getFeatures())

        # Find candidates, if not already in cache
        transform = QgsCoordinateTransform(self.polygon.crs(), self.layer.crs(), self.project)
        polygons.transform(transform)
        candidates = index.intersects(polygons.boundingBox())
        if not candidates:
            Logger.info(
                "Not features in the index matching the bounding box, return the default value {}".format(NO_FEATURES))
            return NO_FEATURES

        # Check real intersection for the candidates
        unique_ids = []
        for candidate_id in candidates:
            feature = self.layer.getFeature(candidate_id)
            if self.spatial_relationship == 'contains':
                if feature.geometry().contains(polygons):
                    unique_ids.append(feature[self.primary_key])
            elif self.spatial_relationship == 'intersects':
                if feature.geometry().intersects(polygons):
                    unique_ids.append(feature[self.primary_key])
            else:
                raise Exception("Spatial relationship unknown")

        # Logger.info("Unique ids = {}".format(','.join([str(f) for f in unique_ids])))
        return self._format_sql_in(self.primary_key, unique_ids)

    @profiling
    @lru_cache(maxsize=CACHE_MAX_SIZE)
    def _features_ids_with_sql_query(self, st_intersect: str) -> str:
        """ List all features using a SQL query.

        Only for QGIS >= 3.10

        :returns: The subset SQL string.
        """
        uri = QgsDataSourceUri(self.layer.source())

        sql = 'SELECT "{pk}" FROM "{schema}"."{table}" WHERE {st_intersect}'.format(
            pk=self.primary_key,
            schema=uri.schema(),
            table=uri.table(),
            st_intersect=st_intersect,
        )
        Logger.info(
            "Requesting the database about IDs to filter with {}...".format(sql[0:90]))

        results = self.sql_query(uri, sql)
        unique_ids = [str(row[0]) for row in results]

        return self._format_sql_in(self.primary_key, unique_ids)

    @classmethod
    def _format_sql_in(cls, primary_key: str, values: Union[list, Tuple]) -> str:
        """Format the SQL IN statement."""
        if not values:
            Logger.info('No values, returning default NO VALUES {}'.format(NO_FEATURES))
            return NO_FEATURES

        cleaned = []
        for value in values:
            if isinstance(value, str):
                cleaned.append("'{}'".format(value))
            else:
                cleaned.append(str(value))

        return '"{pk}" IN ( {values} )'.format(pk=primary_key, values=' , '.join(cleaned))

    @classmethod
    def _format_sql_st_relationship(
            cls,
            filtered_crs: QgsCoordinateReferenceSystem,
            filtering_crs: QgsCoordinateReferenceSystem,
            geom_field: str,
            polygons: QgsGeometry,
            use_st_intersect: bool,
            use_centroid: bool,
    ) -> str:
        """If layer is of type PostgreSQL, use a simple ST_Intersects/ST_Contains.

        :returns: The subset SQL string.
        """
        geom = "ST_GeomFromText('{geom}')".format(geom=polygons.asWkt(6 if filtering_crs.isGeographic() else 2))
        geom = "ST_SetSRID({geom}, {from_crs})".format(
            geom=geom,
            from_crs=filtering_crs.postgisSrid(),
        )
        if filtering_crs != filtered_crs:
            geom = "ST_Transform({geom}, {to_crs})".format(
                geom=geom,
                to_crs=filtered_crs.postgisSrid(),
            )

        if use_centroid:
            geom_field = "ST_Centroid(\"{geom_field}\")".format(geom_field=geom_field)
        else:
            geom_field = "\"{geom_field}\"".format(geom_field=geom_field)

        sql = """
ST_{function}(
    {geom},
    {geom_field}
)""".format(
            function="Intersects" if use_st_intersect else "Contains",
            geom_field=geom_field,
            geom=geom,
        )
        return sql

    @classmethod
    def _format_qgis_expression_relationship(
            cls,
            filtered_crs: QgsCoordinateReferenceSystem,
            filtering_crs: QgsCoordinateReferenceSystem,
            polygons: QgsGeometry,
            use_st_intersect: bool,
            use_centroid: bool,
    ) -> str:
        """Build the filter with a QGIS expression.

        :returns: The QGIS expression
        """
        geom = "geom_from_wkt('{wkt}')".format(
            wkt=polygons.asWkt(6 if filtering_crs.isGeographic() else 2)
        )
        if filtering_crs != filtered_crs:
            geom = "transform({geom}, '{from_crs}', '{to_crs}')".format(
                geom=geom,
                from_crs=filtering_crs.authid(),
                to_crs=filtered_crs.authid()
            )

        if use_centroid:
            current_geometry = "centroid($geometry)"
        else:
            current_geometry = "$geometry"

        expression = """
{function}(
    {geom},
    {current_geometry}
)""".format(
            function="intersects" if use_st_intersect else "contains",
            geom=geom,
            current_geometry=current_geometry,
            )
        return expression
