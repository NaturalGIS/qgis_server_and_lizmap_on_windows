<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lib/jelix-modules/jacl2db/daos/jacl2group.dao.xml') > 1647273805)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jacl2db_Jx_jacl2group_Jx_sqlite3 extends jDaoRecordBase {
 public $id_aclgrp;
 public $name;
 public $grouptype=0;
 public $ownerlogin;
   public function getSelector() { return "jacl2db~jacl2group"; }
   public function getProperties() { return cDao_jacl2db_Jx_jacl2group_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jacl2db_Jx_jacl2group_Jx_sqlite3::$_pkFields; }
}

class cDao_jacl2db_Jx_jacl2group_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'grp' => 
  array (
    'name' => 'grp',
    'realname' => 'jacl2_group',
    'pk' => 
    array (
      0 => 'id_aclgrp',
    ),
    'fields' => 
    array (
      0 => 'id_aclgrp',
      1 => 'name',
      2 => 'grouptype',
      3 => 'ownerlogin',
    ),
  ),
);
   protected $_primaryTable = 'grp';
   protected $_selectClause='SELECT grp.id_aclgrp as id_aclgrp, grp.name as name, grp.grouptype as grouptype, grp.ownerlogin as ownerlogin';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jacl2db_Jx_jacl2group_Jx_sqlite3';
   protected $_daoSelector = 'jacl2db~jacl2group';
   public static $_properties = array (
  'id_aclgrp' => 
  array (
    'name' => 'id_aclgrp',
    'fieldName' => 'id_aclgrp',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'grp',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 60,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'name' => 
  array (
    'name' => 'name',
    'fieldName' => 'name',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'grp',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 150,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'grouptype' => 
  array (
    'name' => 'grouptype',
    'fieldName' => 'grouptype',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'tinyint',
    'unifiedType' => 'integer',
    'table' => 'grp',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => 0,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'ownerlogin' => 
  array (
    'name' => 'ownerlogin',
    'fieldName' => 'ownerlogin',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'grp',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 50,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
);
   public static $_pkFields = array('id_aclgrp');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('jacl2_group').' AS grp';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  grp.id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).'';
}
public function insert ($record){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('jacl2_group').' (
id_aclgrp,name,grouptype,ownerlogin
) VALUES (
'.($record->id_aclgrp === null ? 'NULL' : $this->_conn->quote2($record->id_aclgrp,false)).', '.($record->name === null ? 'NULL' : $this->_conn->quote2($record->name,false)).', '.($record->grouptype === null ? 'NULL' : intval($record->grouptype)).', '.($record->ownerlogin === null ? 'NULL' : $this->_conn->quote2($record->ownerlogin,false)).'
)';
   $result = $this->_conn->exec ($query);
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('jacl2_group').' SET 
 name= '.($record->name === null ? 'NULL' : $this->_conn->quote2($record->name,false)).', grouptype= '.($record->grouptype === null ? 'NULL' : intval($record->grouptype)).', ownerlogin= '.($record->ownerlogin === null ? 'NULL' : $this->_conn->quote2($record->ownerlogin,false)).'
 where  id_aclgrp'.' = '.$this->_conn->quote($record->id_aclgrp).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function getDefaultGroups (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  grp.grouptype = 1 AND grp.id_aclgrp <> \'__anonymous\'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function findAllPublicGroup (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  grp.grouptype <> 2 AND grp.id_aclgrp <> \'__anonymous\' ORDER BY grp.name asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getPrivateGroup ($login){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  grp.grouptype = 2 AND grp.ownerlogin '.($login === null ? 'IS NULL' : ' = '.$this->_conn->quote2($login,false)).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getGroupByCode ($code){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  grp.id_aclgrp '.' = '.$this->_conn->quote($code).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function setToDefault ($group){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jacl2_group').' SET 
 grouptype= 1';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).' AND id_aclgrp <> \'__anonymous\'';
    return $this->_conn->exec ($__query);
}
 function setToNormal ($group){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jacl2_group').' SET 
 grouptype= 0';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).' AND id_aclgrp <> \'__anonymous\'';
    return $this->_conn->exec ($__query);
}
 function changeName ($group, $name){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jacl2_group').' SET 
 name= '.($name === null ? 'NULL' : $this->_conn->quote2($name,false)).'';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).' AND id_aclgrp <> \'__anonymous\'';
    return $this->_conn->exec ($__query);
}

}
 return true; }