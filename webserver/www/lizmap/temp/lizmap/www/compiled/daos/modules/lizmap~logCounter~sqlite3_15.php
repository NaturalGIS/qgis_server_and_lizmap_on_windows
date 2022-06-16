<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/daos/logCounter.dao.xml') > 1616160726)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_lizmap_Jx_logCounter_Jx_sqlite3 extends jDaoRecordBase {
 public $id;
 public $key;
 public $counter;
 public $repository;
 public $project;
   public function getSelector() { return "lizmap~logCounter"; }
   public function getProperties() { return cDao_lizmap_Jx_logCounter_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_lizmap_Jx_logCounter_Jx_sqlite3::$_pkFields; }
}

class cDao_lizmap_Jx_logCounter_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'log_counter' => 
  array (
    'name' => 'log_counter',
    'realname' => 'log_counter',
    'pk' => 
    array (
      0 => 'id',
    ),
    'fields' => 
    array (
      0 => 'id',
      1 => 'key',
      2 => 'counter',
      3 => 'repository',
      4 => 'project',
    ),
  ),
);
   protected $_primaryTable = 'log_counter';
   protected $_selectClause='SELECT log_counter.id as id, log_counter.key as key, log_counter.counter as counter, log_counter.repository as repository, log_counter.project as project';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_lizmap_Jx_logCounter_Jx_sqlite3';
   protected $_daoSelector = 'lizmap~logCounter';
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
    'datatype' => 'integer',
    'unifiedType' => 'integer',
    'table' => 'log_counter',
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
  'key' => 
  array (
    'name' => 'key',
    'fieldName' => 'key',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_counter',
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
  'counter' => 
  array (
    'name' => 'counter',
    'fieldName' => 'counter',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'unifiedType' => 'integer',
    'table' => 'log_counter',
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
  'repository' => 
  array (
    'name' => 'repository',
    'fieldName' => 'repository',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_counter',
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
  'project' => 
  array (
    'name' => 'project',
    'fieldName' => 'project',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_counter',
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
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('log_counter').' AS log_counter';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  log_counter.id'.' = '.intval($id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id'.' = '.intval($id).'';
}
public function insert ($record){
 if($record->id > 0 ){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('log_counter').' (
id,key,counter,repository,project
) VALUES (
'.($record->id === null ? 'NULL' : intval($record->id)).', '.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', '.($record->counter === null ? 'NULL' : intval($record->counter)).', '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).'
)';
}else{
    $query = 'INSERT INTO '.$this->_conn->prefixTable('log_counter').' (
key,counter,repository,project
) VALUES (
'.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', '.($record->counter === null ? 'NULL' : intval($record->counter)).', '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).'
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
   $query = 'UPDATE '.$this->_conn->prefixTable('log_counter').' SET 
 key= '.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', counter= '.($record->counter === null ? 'NULL' : intval($record->counter)).', repository= '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', project= '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).'
 where  id'.' = '.intval($record->id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function getSortedCounter (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY log_counter.key asc, log_counter.repository asc, log_counter.project asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getSortedCounterByKey ($key){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  log_counter.key '.' = '.$this->_conn->quote($key).' ORDER BY log_counter.key asc, log_counter.repository asc, log_counter.project asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getDistinctCounter ($key, $repository, $project){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  log_counter.key '.' = '.$this->_conn->quote($key).' AND log_counter.repository '.($repository === null ? 'IS NULL' : ' = '.$this->_conn->quote2($repository,false)).' AND log_counter.project '.($project === null ? 'IS NULL' : ' = '.$this->_conn->quote2($project,false)).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}

}
 return true; }