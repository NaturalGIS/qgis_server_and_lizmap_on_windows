<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/daos/geobookmark.dao.xml') > 1616160726)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_lizmap_Jx_geobookmark_Jx_sqlite3 extends jDaoRecordBase {
 public $id;
 public $login;
 public $name;
 public $map;
 public $params;
   public function getSelector() { return "lizmap~geobookmark"; }
   public function getProperties() { return cDao_lizmap_Jx_geobookmark_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_lizmap_Jx_geobookmark_Jx_sqlite3::$_pkFields; }
}

class cDao_lizmap_Jx_geobookmark_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'geobookmark' => 
  array (
    'name' => 'geobookmark',
    'realname' => 'geobookmark',
    'pk' => 
    array (
      0 => 'id',
    ),
    'fields' => 
    array (
      0 => 'id',
      1 => 'login',
      2 => 'name',
      3 => 'map',
      4 => 'params',
    ),
  ),
);
   protected $_primaryTable = 'geobookmark';
   protected $_selectClause='SELECT geobookmark.id as id, geobookmark.usr_login as login, geobookmark.bname as name, geobookmark.bmap as map, geobookmark.bparams as params';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_lizmap_Jx_geobookmark_Jx_sqlite3';
   protected $_daoSelector = 'lizmap~geobookmark';
   public static $_properties = array (
  'id' => 
  array (
    'name' => 'id',
    'fieldName' => 'id',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'serial',
    'unifiedType' => 'integer',
    'table' => 'geobookmark',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => true,
    'comment' => '',
  ),
  'login' => 
  array (
    'name' => 'login',
    'fieldName' => 'usr_login',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'geobookmark',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'name' => 
  array (
    'name' => 'name',
    'fieldName' => 'bname',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'geobookmark',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'map' => 
  array (
    'name' => 'map',
    'fieldName' => 'bmap',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'geobookmark',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'params' => 
  array (
    'name' => 'params',
    'fieldName' => 'bparams',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'geobookmark',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
);
   public static $_pkFields = array('id');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('geobookmark').' AS geobookmark';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  geobookmark.id'.' = '.intval($id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id'.' = '.intval($id).'';
}
public function insert ($record){
 if($record->id > 0 ){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('geobookmark').' (
id,usr_login,bname,bmap,bparams
) VALUES (
'.($record->id === null ? 'NULL' : intval($record->id)).', '.($record->login === null ? 'NULL' : $this->_conn->quote2($record->login,false)).', '.($record->name === null ? 'NULL' : $this->_conn->quote2($record->name,false)).', '.($record->map === null ? 'NULL' : $this->_conn->quote2($record->map,false)).', '.($record->params === null ? 'NULL' : $this->_conn->quote2($record->params,false)).'
)';
}else{
    $query = 'INSERT INTO '.$this->_conn->prefixTable('geobookmark').' (
usr_login,bname,bmap,bparams
) VALUES (
'.($record->login === null ? 'NULL' : $this->_conn->quote2($record->login,false)).', '.($record->name === null ? 'NULL' : $this->_conn->quote2($record->name,false)).', '.($record->map === null ? 'NULL' : $this->_conn->quote2($record->map,false)).', '.($record->params === null ? 'NULL' : $this->_conn->quote2($record->params,false)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id < 1 ) 
       $record->id= $this->_conn->lastInsertId();
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('geobookmark').' SET 
 usr_login= '.($record->login === null ? 'NULL' : $this->_conn->quote2($record->login,false)).', bname= '.($record->name === null ? 'NULL' : $this->_conn->quote2($record->name,false)).', bmap= '.($record->map === null ? 'NULL' : $this->_conn->quote2($record->map,false)).', bparams= '.($record->params === null ? 'NULL' : $this->_conn->quote2($record->params,false)).'
 where  id'.' = '.intval($record->id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }


}
 return true; }