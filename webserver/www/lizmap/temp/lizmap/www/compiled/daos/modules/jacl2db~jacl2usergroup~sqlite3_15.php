<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lib/jelix-modules/jacl2db/daos/jacl2usergroup.dao.xml') > 1647273805)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jacl2db_Jx_jacl2usergroup_Jx_sqlite3 extends jDaoRecordBase {
 public $login;
 public $id_aclgrp;
   public function getSelector() { return "jacl2db~jacl2usergroup"; }
   public function getProperties() { return cDao_jacl2db_Jx_jacl2usergroup_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jacl2db_Jx_jacl2usergroup_Jx_sqlite3::$_pkFields; }
}

class cDao_jacl2db_Jx_jacl2usergroup_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'ug' => 
  array (
    'name' => 'ug',
    'realname' => 'jacl2_user_group',
    'pk' => 
    array (
      0 => 'login',
      1 => 'id_aclgrp',
    ),
    'fields' => 
    array (
      0 => 'login',
      1 => 'id_aclgrp',
    ),
  ),
);
   protected $_primaryTable = 'ug';
   protected $_selectClause='SELECT ug.login as login, ug.id_aclgrp as id_aclgrp';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jacl2db_Jx_jacl2usergroup_Jx_sqlite3';
   protected $_daoSelector = 'jacl2db~jacl2usergroup';
   public static $_properties = array (
  'login' => 
  array (
    'name' => 'login',
    'fieldName' => 'login',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'ug',
    'updatePattern' => '',
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
    'table' => 'ug',
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
);
   public static $_pkFields = array('login','id_aclgrp');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('jacl2_user_group').' AS ug';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  ug.login'.' = '.$this->_conn->quote($login).' AND ug.id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  login'.' = '.$this->_conn->quote($login).' AND id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).'';
}
public function insert ($record){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('jacl2_user_group').' (
login,id_aclgrp
) VALUES (
'.($record->login === null ? 'NULL' : $this->_conn->quote2($record->login,false)).', '.($record->id_aclgrp === null ? 'NULL' : $this->_conn->quote2($record->id_aclgrp,false)).'
)';
   $result = $this->_conn->exec ($query);
    return $result;
}
public function update ($record){
     throw new jException('jelix~dao.error.update.impossible',array('jacl2db~jacl2usergroup','C:\webserver\www\lizmap\lib/jelix-modules/jacl2db/daos/jacl2usergroup.dao.xml'));
 }
 function getGroupsUser ($login){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  ug.login '.' = '.$this->_conn->quote($login).'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getUsersGroup ( $grp, $ordre='asc'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  ug.id_aclgrp '.' = '.$this->_conn->quote($grp).' ORDER BY ug.login '.( strtolower($ordre) =='asc'?'asc':'desc').'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getUsersGroupLimit ( $grp, $offset, $count, $ordre='asc'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  ug.id_aclgrp '.' = '.$this->_conn->quote($grp).' ORDER BY ug.login '.( strtolower($ordre) =='asc'?'asc':'desc').'';
    $__rs = $this->_conn->limitQuery($__query, $offset, $count);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getUsersGroupCount ($grp){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
    $__query .=' WHERE  ug.id_aclgrp '.' = '.$this->_conn->quote($grp).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function getUsersGroupLimitAndFilter ( $grp, $login, $offset, $count, $ordre='asc'){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  ug.id_aclgrp '.' = '.$this->_conn->quote($grp).' AND ug.login '.' LIKE '.$this->_conn->quote($login).' ORDER BY ug.login '.( strtolower($ordre) =='asc'?'asc':'desc').'';
    $__rs = $this->_conn->limitQuery($__query, $offset, $count);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getUsersGroupCountAndFilter ($grp, $login){
    $__query = 'SELECT COUNT(*) as c '.$this->_fromClause.$this->_whereClause;
    $__query .=' WHERE  ug.id_aclgrp '.' = '.$this->_conn->quote($grp).' AND ug.login '.' LIKE '.$this->_conn->quote($login).'';
    $__rs = $this->_conn->query($__query);
    $__res = $__rs->fetch();
    return intval($__res->c);
}
 function deleteByUser ($login){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_user_group').' ';
$__query .=' WHERE  login '.' = '.$this->_conn->quote($login).'';
    return $this->_conn->exec ($__query);
}
 function deleteByGroup ($grp){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_user_group').' ';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($grp).'';
    return $this->_conn->exec ($__query);
}

}
 return true; }