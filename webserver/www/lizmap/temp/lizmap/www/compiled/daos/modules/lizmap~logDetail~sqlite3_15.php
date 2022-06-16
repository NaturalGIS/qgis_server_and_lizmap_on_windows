<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/daos/logDetail.dao.xml') > 1616160726)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_lizmap_Jx_logDetail_Jx_sqlite3 extends jDaoRecordBase {
 public $id;
 public $key;
 public $timestamp;
 public $user;
 public $content;
 public $repository;
 public $project;
 public $ip;
   public function getSelector() { return "lizmap~logDetail"; }
   public function getProperties() { return cDao_lizmap_Jx_logDetail_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_lizmap_Jx_logDetail_Jx_sqlite3::$_pkFields; }
}

class cDao_lizmap_Jx_logDetail_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'log_detail' => 
  array (
    'name' => 'log_detail',
    'realname' => 'log_detail',
    'pk' => 
    array (
      0 => 'id',
    ),
    'fields' => 
    array (
      0 => 'id',
      1 => 'key',
      2 => 'timestamp',
      3 => 'user',
      4 => 'content',
      5 => 'repository',
      6 => 'project',
      7 => 'ip',
    ),
  ),
);
   protected $_primaryTable = 'log_detail';
   protected $_selectClause='SELECT log_detail.id as id, log_detail.log_key as key, log_detail.log_timestamp as timestamp, log_detail.log_user as user, log_detail.log_content as content, log_detail.log_repository as repository, log_detail.log_project as project, log_detail.log_ip as ip';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_lizmap_Jx_logDetail_Jx_sqlite3';
   protected $_daoSelector = 'lizmap~logDetail';
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
    'table' => 'log_detail',
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
    'fieldName' => 'log_key',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_detail',
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
  'timestamp' => 
  array (
    'name' => 'timestamp',
    'fieldName' => 'log_timestamp',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'datetime',
    'unifiedType' => 'datetime',
    'table' => 'log_detail',
    'updatePattern' => '%s',
    'insertPattern' => 'now()',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'user' => 
  array (
    'name' => 'user',
    'fieldName' => 'log_user',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_detail',
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
  'content' => 
  array (
    'name' => 'content',
    'fieldName' => 'log_content',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'log_detail',
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
    'fieldName' => 'log_repository',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_detail',
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
    'fieldName' => 'log_project',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_detail',
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
  'ip' => 
  array (
    'name' => 'ip',
    'fieldName' => 'log_ip',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'log_detail',
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
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('log_detail').' AS log_detail';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  log_detail.id'.' = '.intval($id).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id'.' = '.intval($id).'';
}
public function insert ($record){
 if($record->id > 0 ){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('log_detail').' (
id,log_key,log_timestamp,log_user,log_content,log_repository,log_project,log_ip
) VALUES (
'.($record->id === null ? 'NULL' : intval($record->id)).', '.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', datetime(\'now\', \'localtime\'), '.($record->user === null ? 'NULL' : $this->_conn->quote2($record->user,false)).', '.($record->content === null ? 'NULL' : $this->_conn->quote2($record->content,false)).', '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).', '.($record->ip === null ? 'NULL' : $this->_conn->quote2($record->ip,false)).'
)';
}else{
    $query = 'INSERT INTO '.$this->_conn->prefixTable('log_detail').' (
log_key,log_timestamp,log_user,log_content,log_repository,log_project,log_ip
) VALUES (
'.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', datetime(\'now\', \'localtime\'), '.($record->user === null ? 'NULL' : $this->_conn->quote2($record->user,false)).', '.($record->content === null ? 'NULL' : $this->_conn->quote2($record->content,false)).', '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).', '.($record->ip === null ? 'NULL' : $this->_conn->quote2($record->ip,false)).'
)';
}
   $result = $this->_conn->exec ($query);
   if(!$result)
       return false;
   if($record->id < 1 ) 
       $record->id= $this->_conn->lastInsertId();
  $query ='SELECT log_timestamp as timestamp FROM '.$this->_conn->prefixTable('log_detail').' WHERE  id'.' = '.intval($record->id).'';
  $rs  =  $this->_conn->query ($query);
  $newrecord =  $rs->fetch ();
  $record->timestamp = $newrecord->timestamp;
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('log_detail').' SET 
 log_key= '.($record->key === null ? 'NULL' : $this->_conn->quote2($record->key,false)).', log_timestamp= '.($record->timestamp === null ? 'NULL' : $this->_conn->quote2($record->timestamp,false)).', log_user= '.($record->user === null ? 'NULL' : $this->_conn->quote2($record->user,false)).', log_content= '.($record->content === null ? 'NULL' : $this->_conn->quote2($record->content,false)).', log_repository= '.($record->repository === null ? 'NULL' : $this->_conn->quote2($record->repository,false)).', log_project= '.($record->project === null ? 'NULL' : $this->_conn->quote2($record->project,false)).', log_ip= '.($record->ip === null ? 'NULL' : $this->_conn->quote2($record->ip,false)).'
 where  id'.' = '.intval($record->id).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function getDetailRange ($offset, $limit){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY log_detail.id desc';
    $__rs = $this->_conn->limitQuery($__query, $offset, $limit);
    $this->finishInitResultSet($__rs);
    return $__rs;
}

}
 return true; }