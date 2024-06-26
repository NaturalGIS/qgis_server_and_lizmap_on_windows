<?php
/**
 * Give access to qgis mapLayer configuration.
 *
 * @author    3liz
 * @copyright 2013-2019 3liz
 *
 * @see      http://3liz.com
 *
 * @license Mozilla Public License : http://www.mozilla.org/MPL/
 */
class qgisVectorLayer extends qgisMapLayer
{
    // layer type
    protected $type = 'vector';

    protected $fields = array();

    /**
     * @var string[] list of aliases name for each fields
     */
    protected $aliases = array();

    /**
     * @var string[] list of default value (as QGIS expressions) for each fields
     */
    protected $defaultValues = array();

    /**
     * @var string[] list of constraints for each fields (type of contraints and if it is notNull, unique and/or expression contraint)
     */
    protected $constraints = array();

    protected $wfsFields = array();

    /**
     * @var null|object connection parameters
     */
    protected $dtParams;

    /**
     * @var null|jDbConnection
     */
    protected $connection;

    /**
     * @var string the jDb profile to use for the connection
     */
    protected $dbProfile;

    /** @var jDbFieldProperties[] */
    protected $dbFieldList;

    protected $dbFieldsInfo;

    // Map data type as geometry type
    private $geometryDatatypeMap = array(
        'point', 'linestring', 'polygon', 'multipoint',
        'multilinestring', 'multipolygon', 'geometrycollection', 'geometry',
    );

    /**
     * constructor.
     *
     * @param lizmapProject|qgisProject $project
     * @param array                     $propLayer list of properties values
     */
    public function __construct($project, $propLayer)
    {
        parent::__construct($project, $propLayer);
        $this->fields = $propLayer['fields'];
        $this->aliases = $propLayer['aliases'];
        $this->defaultValues = $propLayer['defaults'];
        $this->constraints = $propLayer['constraints'];
        $this->wfsFields = $propLayer['wfsFields'];
    }

    /**
     * Get the WFS typename for this layer.
     *
     * We need to get either the shortname or the layer name
     * and replace all spaces by underscore
     *
     * @return string The WFS typename of the layer
     */
    public function getWfsTypeName()
    {
        // If we have a short name, we should use it
        $typename = $this->getShortName();
        if (!$typename) {
            $typename = $this->getName();
        }

        return str_replace(' ', '_', $typename);
    }

    public function getFields()
    {
        return $this->fields;
    }

    /**
     * list of aliases.
     *
     * @return string[]
     */
    public function getAliasFields()
    {
        return $this->aliases;
    }

    /**
     * List of default values for each fields.
     *
     * Values are QGIS expressions or may be null when no default value is given
     *
     * @return string[]
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

    /**
     * Get the QGIS expression of the default value of the given field.
     *
     * @param string $fieldName
     *
     * @return null|string null if there is no default value
     */
    public function getDefaultValue($fieldName)
    {
        if (isset($this->defaultValues[$fieldName])) {
            return $this->defaultValues[$fieldName];
        }

        return null;
    }

    /**
     * list of constraints.
     *
     * @return string[]
     */
    public function getConstraintsList()
    {
        return $this->constraints;
    }

    /**
     * Get the QGIS constraints of the given field.
     *
     * @param string $fieldName
     *
     * @return string[] the constraints of the field
     */
    public function getConstraints($fieldName)
    {
        if (isset($this->constraints[$fieldName])) {
            return $this->constraints[$fieldName];
        }

        return array(
            'constraints' => 0,
            'notNull' => false,
            'unique' => false,
            'exp' => false,
        );
    }

    public function getWfsFields()
    {
        return $this->wfsFields;
    }

    /**
     * @return object
     */
    public function getDatasourceParameters()
    {
        if ($this->dtParams) {
            return $this->dtParams;
        }

        $datasourceParser = new qgisVectorLayerDatasource(
            $this->provider,
            $this->datasource
        );
        $parameters = array(
            'dbname', 'service', 'host', 'port', 'user', 'password',
            'sslmode', 'key', 'estimatedmetadata', 'selectatid',
            'srid', 'type', 'checkPrimaryKeyUnicity',
            'table', 'geocol', 'sql', 'schema', 'tablename',
        );

        foreach ($parameters as $param) {
            $ds[$param] = $datasourceParser->getDatasourceParameter($param);
        }

        $this->dtParams = (object) $ds;

        return $this->dtParams;
    }

    /**
     * Give the jDb profile name for the database connection.
     *
     * This method is public so it can be used by custom modules. Sometimes
     * getDatasourceConnection() is not useful, as we could need the profile
     * to give to jDao or other components that need a profile, not a connection
     *
     * @param int  $timeout                default timeout for the connection
     * @param bool $setSearchPathFromLayer If true, the layer schema is used to set the search_path.
     *                                     Default to True to keep the same behavior as did the previous version of this method.
     *
     * @throws jException
     *
     * @return null|string null if there is an issue or no connection parameters
     */
    public function getDatasourceProfile($timeout = 30, $setSearchPathFromLayer = true)
    {
        if ($this->dbProfile !== null) {
            return $this->dbProfile;
        }

        $dtParams = $this->getDatasourceParameters();
        if ($this->provider == 'spatialite') {
            $spatialiteExt = $this->project->getSpatialiteExtension();
            $repository = $this->project->getRepository();
            $jdbParams = array(
                'driver' => 'sqlite3',
                'database' => realpath($repository->getPath().$dtParams->dbname),
                'extensions' => $spatialiteExt,
            );
        } elseif ($this->provider == 'postgres') {
            // no persistent connexions, it may reach max connections to pgsql if
            // they are not closed correctly, and then reopened
            if (!empty($dtParams->service)) {
                $jdbParams = array(
                    'driver' => 'pgsql',
                    'service' => $dtParams->service,
                    'timeout' => $timeout,
                );
                // Database may be used since dbname
                // is not mandatory in service file
                if (!empty($dtParams->dbname)) {
                    $jdbParams['database'] = $dtParams->dbname;
                }
            } else {
                $jdbParams = array(
                    'driver' => 'pgsql',
                    'host' => $dtParams->host,
                    'port' => (int) $dtParams->port,
                    'database' => $dtParams->dbname,
                    'user' => $dtParams->user,
                    'password' => $dtParams->password,
                    'timeout' => $timeout,
                );
            }
            if (!empty($dtParams->schema) && $setSearchPathFromLayer) {
                $jdbParams['search_path'] = '"'.$dtParams->schema.'",public';
            }
        } elseif ($this->provider == 'ogr'
            and preg_match('#(gpkg|sqlite)$#', $dtParams->dbname)) {
            $spatialiteExt = $this->project->getSpatialiteExtension();
            $repository = $this->project->getRepository();
            $jdbParams = array(
                'driver' => 'sqlite3',
                'database' => realpath($repository->getPath().$dtParams->dbname),
                'extensions' => $spatialiteExt,
            );
        } else {
            return null;
        }

        // construct the profile name from a sha1 of parameters, so the profile
        // may be the same as an other layer it this other layer has same db
        // parameters. So we can share the profile (and so share the same connection)
        // instead of creating a new one for each layer
        $this->dbProfile = 'layerdb_'.sha1(json_encode($jdbParams));

        try {
            // try to get the profile, it may be already created for an other layer
            jProfiles::get('jdb', $this->dbProfile, true);
        } catch (Exception $e) {
            // create the profile
            jProfiles::createVirtualProfile('jdb', $this->dbProfile, $jdbParams);
        }

        return $this->dbProfile;
    }

    /**
     * @throws jException
     *
     * @return jDbConnection
     */
    public function getDatasourceConnection()
    {
        if ($this->connection) {
            return $this->connection;
        }

        if ($this->provider != 'spatialite' && $this->provider != 'postgres' and !(preg_match('#layername=#', $this->datasource))) {
            jLog::log('Unknown provider "'.$this->provider.'" to get connection!', 'error');

            return null;
        }

        $profile = $this->getDatasourceProfile();
        $cnx = jDb::getConnection($profile);
        $this->connection = $cnx;

        return $cnx;
    }

    /**
     * @return jDbFieldProperties[]
     */
    public function getDbFieldList()
    {
        if ($this->dbFieldList) {
            return $this->dbFieldList;
        }

        $dtParams = $this->getDatasourceParameters();
        if (!$dtParams) {
            return array();
        }

        $cnx = $this->getDatasourceConnection();
        if (!$cnx) {
            return array();
        }

        $tools = $cnx->tools();
        $sequence = null;

        $this->dbFieldList = $tools->getFieldList($dtParams->tablename, $sequence, $dtParams->schema);

        return $this->dbFieldList;
    }

    /**
     * @return null|qgisLayerDbFieldsInfo
     */
    public function getDbFieldsInfo()
    {
        if ($this->dbFieldsInfo) {
            return $this->dbFieldsInfo;
        }

        $dtParams = $this->getDatasourceParameters();
        if (!$dtParams) {
            jLog::log('Cant get datasource params for the layer "'.$this->name.'"', 'error');

            return null;
        }

        $cnx = $this->getDatasourceConnection();
        if (!$cnx) {
            jLog::log('Cant get datasource connection for the layer "'.$this->name.'"', 'error');

            return null;
        }

        $fields = $this->getDbFieldList();
        $wfsFields = $this->getWfsFields();

        $dbInfo = new qgisLayerDbFieldsInfo($cnx);
        $dbInfo->dataFields = array();
        foreach ($fields as $fieldName => $prop) {
            if (in_array($fieldName, $wfsFields) || in_array(strtolower($prop->type), $this->geometryDatatypeMap)) {
                $dbInfo->dataFields[$fieldName] = $prop;
            }
        }

        $dbInfo->primaryKeys = array();
        foreach ($dbInfo->dataFields as $fieldName => $prop) {
            // Detect primary key column
            if ($prop->primary && !in_array($fieldName, $dbInfo->primaryKeys)) {
                $dbInfo->primaryKeys[] = $fieldName;
            }

            // Detect geometry column
            if (in_array(strtolower($prop->type), $this->geometryDatatypeMap)) {
                $dbInfo->geometryColumn = $fieldName;
                $dbInfo->geometryType = strtolower($prop->type);
                // If postgresql, get real geometryType from pg_attribute (jelix prop gives 'geometry')
                // Issue #902, "geometry_columns" is not giving the Z value
                if ($this->provider == 'postgres' and $dbInfo->geometryType == 'geometry') {
                    $tablename = '"'.$dtParams->schema.'"."'.$dtParams->tablename.'"';
                    $sql = 'SELECT format_type(atttypid,atttypmod) AS type';
                    $sql .= ' FROM pg_attribute';
                    $sql .= ' WHERE attname = '.$cnx->quote($dbInfo->geometryColumn);
                    $sql .= ' AND attrelid = '.$cnx->quote($tablename).'::regclass';
                    $res = $cnx->query($sql);
                    $res = $res->fetch();
                    // It returns something like "geometry(PointZ,32620) as type"
                    if ($res && preg_match('/^geometry\\(([^,\\)]*)/i', $res->type, $m)) {
                        $dbInfo->geometryType = strtolower($m[1]);
                    }
                }
            }
        }

        // For views : add key from datasource
        if (count($dbInfo->primaryKeys) == 0 and $dtParams->key) {
            // check if layer is a view
            if ($this->provider == 'postgres') {
                $sql = ' SELECT table_name FROM INFORMATION_SCHEMA.views';
                $sql .= ' WHERE 2>1';
                $sql .= ' AND (table_schema = ANY (current_schemas(false)) OR table_schema = '.$cnx->quote($dtParams->schema).')';
                $sql .= ' AND table_name='.$cnx->quote($dtParams->tablename);
            }
            if ($this->provider == 'spatialite') {
                $sql = " SELECT name FROM sqlite_master WHERE type = 'view'";
                $sql .= ' AND name='.$cnx->quote($dtParams->tablename);
            }
            $res = $cnx->query($sql);
            if ($res->rowCount() > 0) {
                $dbInfo->primaryKeys[] = $dtParams->key;
            }
        }

        $this->dbFieldsInfo = $dbInfo;

        return $this->dbFieldsInfo;
    }

    public function getPrimaryKeyValues($feature)
    {
        $dbFieldsInfo = $this->getDbFieldsInfo();
        $pkVal = array();
        foreach ($dbFieldsInfo->primaryKeys as $key) {
            $pkVal[$key] = $feature->properties->{$key};
        }

        return $pkVal;
    }

    public function getDbFieldDefaultValues()
    {
        $dbFieldsInfo = $this->getDbFieldsInfo();

        $provider = $this->getProvider();
        $cnx = null;
        if ($provider == 'postgres') {
            $cnx = $this->getDatasourceConnection();
        }

        $defaultValues = array();
        foreach ($dbFieldsInfo->dataFields as $ref => $prop) {
            if (!$prop->hasDefault) {
                continue;
            }
            if ($prop->default == '') {
                continue;
            }
            $defaultValues[$ref] = $prop->default;
            // if provider is postgres evaluate default value
            if ($provider == 'postgres') {
                $ds = $cnx->query('SELECT '.$prop->default.' AS v;');
                $d = $ds->fetch();
                if ($d) {
                    $defaultValues[$ref] = $d->v;
                }
            }
        }

        return $defaultValues;
    }

    public function getDbFieldDistinctValues($field)
    {
        $dtParams = $this->getDatasourceParameters();

        // Get database connection object
        $cnx = $this->getDatasourceConnection();

        // Build the SQL query to retrieve data from the table
        $sql = 'SELECT DISTINCT "'.$field.'"';
        $sql .= ' FROM '.$dtParams->table;

        // Run the query and loop through the result to set the form data
        $rs = $cnx->query($sql);
        $values = array();
        foreach ($rs as $record) {
            $values[] = $record->{$field};
        }

        // Add default value
        $dbFieldsInfo = $this->getDbFieldsInfo();
        $dataFields = $dbFieldsInfo->dataFields;
        if (array_key_exists($field, $dataFields)) {
            $prop = $dataFields[$field];
            if ($prop->hasDefault && $prop->default != ''
                && !in_array($prop->default, $values)) {
                $provider = $this->getProvider();
                $cnx = null;
                if ($provider == 'postgres') {
                    $cnx = $this->getDatasourceConnection();
                }
                // if provider is postgres evaluate default value
                if ($provider == 'postgres') {
                    $ds = $cnx->query('SELECT '.$prop->default.' AS v;');
                    $d = $ds->fetch();
                    if ($d && !in_array($d->v, $values)) {
                        array_unshift($values, $d->v);
                    }
                } else {
                    array_unshift($values, $prop->default);
                }
            }
        }

        return $values;
    }

    public function getDbFieldValues($feature)
    {
        $dbFieldsInfo = $this->getDbFieldsInfo();

        $dtParams = $this->getDatasourceParameters();

        // Get database connection object
        $cnx = $this->getDatasourceConnection();

        $geometryColumn = $dbFieldsInfo->geometryColumn;
        // Build the SQL query to retrieve data from the table
        $sql = 'SELECT *';
        if ($geometryColumn != '') {
            $sql .= ', ST_AsText('.$geometryColumn.') AS astext';
        }
        $sql .= ' FROM '.$dtParams->table;

        list($sqlw, $pk) = $this->getPkWhereClause($dbFieldsInfo, $feature);
        $dataFields = $dbFieldsInfo->dataFields;
        $sql .= ' WHERE ';
        $sql .= implode(' AND ', $sqlw);

        // Run the query and loop through the result to set the form data
        $rs = $cnx->query($sql);
        $values = array();
        foreach ($rs as $record) {
            // Loop through the data fields
            foreach ($dataFields as $ref => $prop) {
                $values[$ref] = $record->{$ref};
            }
            // geometry column : override binary with text representation
            if ($geometryColumn != '') {
                $values[$geometryColumn] = $record->astext;
            }
        }

        return $values;
    }

    public function getGeometryAsSql($value)
    {
        $nvalue = "ST_GeomFromText('".filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES)."', ".$this->srid.')';

        // Get database connection object
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        // test type
        $rs = $cnx->query('SELECT GeometryType('.$nvalue.') as geomtype');
        $rs = $rs->fetch();
        $geomtype = strtolower($rs->geomtype);
        if (!preg_match('/'.preg_quote($dbFieldsInfo->geometryType).'/', $geomtype)) {
            if (preg_match('/'.preg_quote(str_replace('multi', '', $dbFieldsInfo->geometryType)).'/', $geomtype)) {
                $nvalue = 'ST_Multi('.$nvalue.')';
            }
        }

        if (substr($dbFieldsInfo->geometryType, -2) == 'zm') {
            $nvalue = 'ST_Force4D('.$nvalue.')';
        } elseif (substr($dbFieldsInfo->geometryType, -1) == 'z') {
            $nvalue = 'ST_Force3DZ('.$nvalue.')';
        } elseif (substr($dbFieldsInfo->geometryType, -1) == 'm') {
            $nvalue = 'ST_Force3DM('.$nvalue.')';
        }

        return $nvalue;
    }

    /**
     * @param qgisLayerDbFieldsInfo $dbFieldsInfo
     * @param object                $feature
     */
    protected function getPkWhereClause($dbFieldsInfo, $feature)
    {
        $sqlw = array();
        $pk = array();
        foreach ($dbFieldsInfo->primaryKeys as $key) {
            $val = $feature->properties->{$key};
            $sqlw[] = $dbFieldsInfo->getSQLRefEquality($key, $val);
            $pk[$key] = $val;
        }

        return array($sqlw, $pk);
    }

    /**
     * @param array $values
     *
     * @throws Exception
     *
     * @return array list of primary keys with their values
     */
    public function insertFeature($values)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        $refs = array();
        $insert = array();
        $primaryKeys = $dbFieldsInfo->primaryKeys;
        $dataLogInfo = array();
        foreach ($values as $ref => $value) {
            // For insert, only for not NULL values to allow serial and default values to work
            if ($value !== 'NULL') {
                $insert[] = $value; //FIXME no $cnx->quote($value) ?
                $refs[] = $cnx->encloseName($ref);
                // For log
                if (in_array($ref, $primaryKeys)) {
                    $val = $value;
                    $dataLogInfo[] = $dbFieldsInfo->getSQLRefEquality($ref, $val);
                }
            }
        }

        $sql = ' INSERT INTO '.$dtParams->table.' (';
        $sql .= implode(', ', $refs);
        $sql .= ' ) VALUES (';
        $sql .= implode(', ', $insert);
        $sql .= ' )';

        // Get select clause for primary keys (used when inserting data in postgresql)
        $returnKeys = array();
        foreach ($primaryKeys as $key) {
            $returnKeys[] = $cnx->encloseName($key);
        }
        $returnKeysString = implode(', ', $returnKeys);
        // For spatialite, we will run a complementary query to retrieve the pkeys
        if ($this->provider == 'postgres') {
            $sql .= '  RETURNING '.$returnKeysString;
        }
        $sql .= ';';

        try {
            // Begin transaction
            $cnx->beginTransaction();
            // Retrieve PK for created objects
            $pkvals = array();
            if ($this->provider == 'postgres') {
                // Query the request
                $rs = $cnx->query($sql);
                foreach ($rs as $line) {
                    foreach ($primaryKeys as $key) {
                        $pkvals[$key] = $line->{$key};
                    }

                    break;
                }
            } else {
                // Exec the request
                $rs = $cnx->exec($sql);
                $sqlpk = 'SELECT '.$returnKeysString.' FROM '.$dtParams->table.' WHERE rowid = last_insert_rowid();';
                $rspk = $cnx->query($sqlpk);
                foreach ($rspk as $line) {
                    foreach ($primaryKeys as $key) {
                        $pkvals[$key] = $line->{$key};
                    }

                    break;
                }
            }
            $cnx->commit();

            // Log
            $content = 'INSERT table='.$dtParams->table;
            if (count($pkvals) > 0) {
                $content .= ', pkeys='.json_encode($pkvals);
            }
            $content .= ', ('.implode(', ', $dataLogInfo).')';
            $eventParams = array(
                'key' => 'editionSaveFeature',
                'content' => $content,
                'repository' => $this->project->getRepository()->getKey(),
                'project' => $this->project->getKey(),
            );
            jEvent::notify('LizLogItem', $eventParams);

            return $pkvals;
        } catch (Exception $e) {
            jLog::log('SQL = '.$sql);

            throw $e;
        }
    }

    /**
     * @param object     $feature
     * @param array      $values
     * @param null|array $loginFilteredLayers array with these keys:
     *                                        - where: SQL WHERE statement
     *                                        - type: 'groups' or 'login'
     *                                        - attribute: filter attribute from the layer
     *
     * @throws Exception
     *
     * @return array list of primary keys with their values
     */
    public function updateFeature($feature, $values, $loginFilteredLayers)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();
        $primaryKeys = $dbFieldsInfo->primaryKeys;

        $update = array();
        foreach ($values as $ref => $value) {
            // For update, do not update primary keys
            if (in_array($ref, $primaryKeys)) {
                continue;
            }
            // For update, keep fields with NULL to allow deletion of values
            $update[] = $cnx->encloseName($ref).'='.$value;
        }

        // SQL for updating on line in the edition table
        $sql = ' UPDATE '.$dtParams->table.' SET ';
        $sql .= implode(', ', $update);

        // Add where clause with primary keys
        list($sqlw, $pk) = $this->getPkWhereClause($dbFieldsInfo, $feature);
        // Store WHere clause to retrieve primary keys in spatialite
        $uwhere = '';
        $uwhere .= ' WHERE ';
        $uwhere .= implode(' AND ', $sqlw);

        // Add login filter if needed
        if ($loginFilteredLayers and is_array($loginFilteredLayers)) {
            $uwhere .= ' AND '.$loginFilteredLayers['where'];
        }
        $sql .= $uwhere;

        // Get select clause for primary keys (used when inserting data in postgresql)
        $returnKeys = array();
        foreach (array_keys($pk) as $key) {
            $returnKeys[] = $cnx->encloseName($key);
        }
        $returnKeysString = implode(', ', $returnKeys);
        // For spatialite, we will run a complementary query to retrieve the pkeys
        if ($this->provider == 'postgres') {
            $sql .= '  RETURNING '.$returnKeysString;
        }
        $sql .= ';';

        try {
            // Begin transaction
            $cnx->beginTransaction();
            // Retrieve PK for created objects
            $pkvals = array();
            if ($this->provider == 'postgres') {
                // Query the request
                $rs = $cnx->query($sql);
                foreach ($rs as $line) {
                    foreach ($dbFieldsInfo->primaryKeys as $key) {
                        $pkvals[$key] = $line->{$key};
                    }

                    break;
                }
            } else {
                // Exec the request
                $cnx->exec($sql);
                $sqlpk = 'SELECT '.$returnKeysString.' FROM '.$dtParams->table.$uwhere;
                $rspk = $cnx->query($sqlpk);
                foreach ($rspk as $line) {
                    foreach ($dbFieldsInfo->primaryKeys as $key) {
                        $pkvals[$key] = $line->{$key};
                    }

                    break;
                }
            }
            $cnx->commit();

            // Log
            $content = 'UPDATE table='.$dtParams->table;
            $content .= ', id='.$feature->id;
            if (count($pkvals) > 0) {
                $content .= ', pkeys='.json_encode($pkvals);
            }
            $content .= ', ('.implode(', ', $update).')';
            $eventParams = array(
                'key' => 'editionSaveFeature',
                'content' => $content,
                'repository' => $this->project->getRepository()->getKey(),
                'project' => $this->project->getKey(),
            );
            jEvent::notify('LizLogItem', $eventParams);

            return $pkvals;
        } catch (Exception $e) {
            jLog::log('SQL = '.$sql, 'error');

            throw $e;
        }
    }

    /**
     * @param object     $feature
     * @param null|array $loginFilteredLayers array with these keys:
     *                                        - where: SQL WHERE statement
     *                                        - type: 'groups' or 'login'
     *                                        - attribute: filter attribute from the layer
     *
     * @throws Exception
     *
     * @return int
     */
    public function deleteFeature($feature, $loginFilteredLayers)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        // SQL for deleting on line in the edition table
        $sql = ' DELETE FROM '.$dtParams->table;

        // Add where clause with primary keys
        list($sqlw, $pkLogInfo) = $this->getPkWhereClause($dbFieldsInfo, $feature);
        $sql .= ' WHERE ';
        $sql .= implode(' AND ', $sqlw);

        // Add login filter if needed
        if ($loginFilteredLayers and is_array($loginFilteredLayers)) {
            $sql .= ' AND '.$loginFilteredLayers['where'];
        }

        try {
            $rs = $cnx->exec($sql);

            // Log
            $content = 'table='.$dtParams->table;
            $content .= ', id='.$feature->id;
            if (count($pkLogInfo) > 0) {
                $content .= ', pk='.implode(',', $pkLogInfo);
            }
            $eventParams = array(
                'key' => 'editionDeleteFeature',
                'content' => $content,
                'repository' => $this->project->getRepository()->getKey(),
                'project' => $this->project->getKey(),
            );
            jEvent::notify('LizLogItem', $eventParams);

            return $rs;
        } catch (Exception $e) {
            jLog::log('SQL = '.$sql);

            throw $e;
        }
    }

    public function editableFeatures()
    {
        $data = array(
            'status' => 'unrestricted',
            'features' => array(),
        );

        $project = $this->getProject();
        $rep = $project->getRepository();

        // login filtered override
        if (jAcl2::check('lizmap.tools.loginFilteredLayers.override', $rep->getKey())) {
            return $data;
        }

        // Get filter by login
        $loginFilter = $project->getLoginFilter($this->getName(), true);
        // login filters array is empty
        if (empty($loginFilter)) {
            return $data;
        }

        // layer not in login filters array
        if (!array_key_exists('filter', $loginFilter)) {
            return $data;
        }

        // Editable features are a restricted list
        $data['status'] = 'restricted';

        // Filter
        $expByUser = $loginFilter['filter'];

        // Get layer WFS typename
        $typename = $this->getWfsTypeName();

        // Get the needed fields to retrieve
        $dbFieldsInfo = $this->getDbFieldsInfo();
        $pKeys = $dbFieldsInfo->primaryKeys;
        $properties = array_merge($pKeys, array($loginFilter['filterAttribute']));

        $params = array(
            'MAP' => $project->getPath(),
            'SERVICE' => 'WFS',
            'VERSION' => '1.0.0',
            'REQUEST' => 'GetFeature',
            'TYPENAME' => $typename,
            'PROPERTYNAME' => implode(',', $properties),
            'OUTPUTFORMAT' => 'GeoJSON',
            'GEOMETRYNAME' => 'none',
            'EXP_FILTER' => $expByUser,
        );

        // Perform request
        $wfsRequest = new lizmapWFSRequest($project, $params);
        $result = $wfsRequest->process();

        // Check code
        if (floor($result->code / 100) >= 4) {
            return $data;
        }

        // Check mime/type
        if (in_array(strtolower($result->mime), array('text/html', 'text/xml'))) {
            return $data;
        }

        // Get data
        $wfsData = $result->data;
        if (property_exists($result, 'file') and $result->file and is_file($wfsData)) {
            $wfsData = jFile::read($wfsData);
        }

        // Check data
        if (!$wfsData) {
            return $data;
        }

        // Get data from layer
        $wfsData = json_decode($wfsData);
        $data['features'] = $wfsData->features;

        return $data;
    }

    /**
     * Link features between 2 tables: one parent layer and one child layer.
     *
     * It runs a SQL query concerning the child layer table:
     * UPDATE child_table
     * SET foreign_key_column = parent_id_value
     * WHERE child_pkey_column = child_id;
     *
     * @param string $foreign_key_column name of the foreign key column in the child layer we need in the update SET
     * @param int    $parent_id_value    id value of the parent layer feature (only one ID allowed) used in the SET
     * @param string $child_pkey_column  name of the primary key column in the child layer, used in the WHERE clause
     * @param array  $child_ids          Primary key values of the child features to be linked. More than one allowed
     *
     * @return array Results of the SQL UPDATE queries
     */
    public function linkChildren($foreign_key_column, $parent_id_value, $child_pkey_column, $child_ids)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        $results = array();
        $foreign_key_setter = $dbFieldsInfo->getSQLRefEquality($foreign_key_column, (int) $parent_id_value);
        foreach ($child_ids as $child_id) {
            $child_filter_based_on_pk = $dbFieldsInfo->getSQLRefEquality($child_pkey_column, (int) $child_id);

            // Build SQL
            $sql = ' UPDATE '.$dtParams->table;
            $sql .= ' SET '.$foreign_key_setter;
            $sql .= ' WHERE '.$child_filter_based_on_pk;
            $sql .= ';';

            try {
                $results[] = $cnx->exec($sql);
            } catch (Exception $e) {
                jLog::log('SQL = '.$sql);

                throw $e;
            }
        }

        return $results;
    }

    /**
     * Link features between 2 tables by creating the needed lines
     * in a third pivot table, for a many-to-many relation (n-m).
     *
     * It runs one ore many SQL queries concerning the pivot layer table to insert the needed line(s):
     * INSERT INTO pivot_table ("foreign_key_column_a" , "foreign_key_column_b")
     * SELECT parent_a_id_value, parent_b_id_value
     * WHERE NOT EXISTS (
     *     SELECT "foreign_key_column_a" , "foreign_key_column_b"
     *     FROM pivot_table
     *     WHERE "foreign_key_column_a" = parent_a_id_value AND "foreign_key_column_b" = parent_b_id_value
     * );
     *
     * @param string $foreign_key_column_a name of the foreign key column referencing the parent A in the pivot table
     * @param array  $parent_a_ids         values of the ids of the selected features of the parent table A
     * @param string $foreign_key_column_b name of the foreign key column referencing the parent B in the pivot table
     * @param array  $parent_b_ids         values of the ids of the selected features of the parent table B
     *
     * @return array Results of the SQL INSERT queries
     */
    public function insertRelations($foreign_key_column_a, $parent_a_ids, $foreign_key_column_b, $parent_b_ids)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        $results = array();
        foreach ($parent_a_ids as $parent_a_id) {
            $quoted_parent_a_id = $dbFieldsInfo->getQuotedValue($foreign_key_column_a, (int) $parent_a_id);
            foreach ($parent_b_ids as $parent_b_id) {
                $quoted_parent_b_id = $dbFieldsInfo->getQuotedValue($foreign_key_column_b, (int) $parent_b_id);

                // Build SQL
                $sql = ' INSERT INTO '.$dtParams->table.' (';
                $sql .= ' '.$cnx->encloseName($foreign_key_column_a).' , ';
                $sql .= ' '.$cnx->encloseName($foreign_key_column_b).' )';
                $sql .= ' SELECT '.$quoted_parent_a_id.', '.$quoted_parent_b_id;
                $sql .= ' WHERE NOT EXISTS';
                $sql .= ' ( SELECT ';
                $sql .= ' '.$cnx->encloseName($foreign_key_column_a).' , ';
                $sql .= ' '.$cnx->encloseName($foreign_key_column_b).' ';
                $sql .= ' FROM '.$dtParams->table;
                $sql .= ' WHERE '.$cnx->encloseName($foreign_key_column_a).' = '.$quoted_parent_a_id;
                $sql .= ' AND '.$cnx->encloseName($foreign_key_column_b).' = '.$quoted_parent_b_id.')';
                $sql .= ';';

                try {
                    $results[] = $cnx->exec($sql);
                } catch (Exception $e) {
                    jLog::log('SQL = '.$sql);

                    throw $e;
                }
            }
        }

        return $results;
    }

    public function unlinkChild($fkey, $pkey, $pval)
    {
        // Get database connection object
        $dtParams = $this->getDatasourceParameters();
        $cnx = $this->getDatasourceConnection();
        $dbFieldsInfo = $this->getDbFieldsInfo();

        // Build SQL
        $valSQL = $dbFieldsInfo->getSQLRefEquality($pkey, (int) $pval);

        $sql = ' UPDATE '.$dtParams->table;
        $sql .= ' SET '.$cnx->encloseName($fkey).' = NULL';
        $sql .= ' WHERE '.$valSQL;
        $sql .= ';';

        try {
            return $cnx->exec($sql);
        } catch (Exception $e) {
            jLog::log('SQL = '.$sql);

            throw $e;
        }
    }

    public function isEditable()
    {
        $layerName = $this->name;
        $eLayers = $this->project->getEditionLayers();
        if (!property_exists($eLayers, $layerName)) {
            return false;
        }
        $eLayer = $eLayers->{$layerName};
        if ($eLayer->capabilities->modifyGeometry != 'True'
           && $eLayer->capabilities->modifyAttribute != 'True'
           && $eLayer->capabilities->deleteFeature != 'True'
           && $eLayer->capabilities->createFeature != 'True'
        ) {
            return false;
        }

        return true;
    }

    public function getEditionCapabilities()
    {
        $layerName = $this->name;
        $eLayers = $this->project->getEditionLayers();
        if (!property_exists($eLayers, $layerName)) {
            return null;
        }

        return $eLayers->{$layerName};
    }
}
