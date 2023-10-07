__copyright__ = 'Copyright 2021, 3Liz'
__license__ = 'GPL version 3'
__email__ = 'info@3liz.org'

from enum import Enum
from typing import NamedTuple, Union

PLUGIN = 'WfsOutputExtension'

# With Python 3.7, switch to dataclass


class Format(NamedTuple):
    content_type: str
    filename_ext: str
    force_crs: Union[str, None]
    ogr_provider: str
    ogr_datasource_options: tuple
    zip: bool
    ext_to_zip: tuple
    """ Format available for exporting data. """


class OutputFormats(Format, Enum):
    """ Output formats. """
    @classmethod
    def find(cls, filename_ext: str) -> Union[Format, None]:
        """Return the format for the given extension."""
        for format_definition in cls.__members__.values():
            if format_definition.filename_ext == filename_ext:
                return format_definition
        return None

    Shp = Format(
        content_type='application/x-zipped-shp',
        filename_ext='shp',
        force_crs=None,
        ogr_provider='ESRI Shapefile',
        ogr_datasource_options=(),
        zip=True,
        ext_to_zip=('shx', 'dbf', 'prj', 'cpg'),
    )
    Tab = Format(
        content_type='application/x-zipped-tab',
        filename_ext='tab',
        force_crs=None,
        ogr_provider='Mapinfo File',
        ogr_datasource_options=(),
        zip=True,
        ext_to_zip=('dat', 'map', 'id'),
    )
    Mif = Format(
        content_type='application/x-zipped-mif',
        filename_ext='mif',
        force_crs=None,
        ogr_provider='Mapinfo File',
        ogr_datasource_options=('FORMAT=MIF',),
        zip=True,
        ext_to_zip=('mid',),
    )
    Kml = Format(
        content_type='application/vnd.google-earth.kml+xml',
        filename_ext='kml',
        force_crs='EPSG:4326',
        ogr_provider='KML',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
    Gpkg = Format(
        content_type='application/geopackage+vnd.sqlite3',
        filename_ext='gpkg',
        force_crs=None,
        ogr_provider='GPKG',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
    Gpx = Format(
        content_type='application/gpx+xml',
        filename_ext='gpx',
        force_crs='EPSG:4326',
        ogr_provider='GPX',
        ogr_datasource_options=(
            'GPX_USE_EXTENSIONS=YES',
            'GPX_EXTENSIONS_NS=ogr',
            'GPX_EXTENSION_NS_URL=http://osgeo.org/gdal',
        ),
        zip=False,
        ext_to_zip=(),
    )
    Ods = Format(
        content_type='application/vnd.oasis.opendocument.spreadsheet',
        filename_ext='ods',
        force_crs=None,
        ogr_provider='ODS',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
    Xlsx = Format(
        content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        filename_ext='xlsx',
        force_crs=None,
        ogr_provider='XLSX',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
    Csv = Format(
        content_type='text/csv',
        filename_ext='csv',
        force_crs=None,
        ogr_provider='CSV',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
    Fgb = Format(
        content_type='application/x-fgb',
        filename_ext='fgb',
        force_crs=None,
        ogr_provider='FlatGeobuf',
        ogr_datasource_options=(),
        zip=False,
        ext_to_zip=(),
    )
