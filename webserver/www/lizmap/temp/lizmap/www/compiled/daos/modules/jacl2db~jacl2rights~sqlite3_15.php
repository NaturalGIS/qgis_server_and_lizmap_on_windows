<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lib/jelix-modules/jacl2db/daos/jacl2rights.dao.xml') > 1647273805)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jacl2db_Jx_jacl2rights_Jx_sqlite3 extends jDaoRecordBase {
 public $id_aclsbj;
 public $id_aclgrp;
 public $id_aclres;
 public $canceled=0;
   public function getSelector() { return "jacl2db~jacl2rights"; }
   public function getProperties() { return cDao_jacl2db_Jx_jacl2rights_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jacl2db_Jx_jacl2rights_Jx_sqlite3::$_pkFields; }
}

class cDao_jacl2db_Jx_jacl2rights_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'r' => 
  array (
    'name' => 'r',
    'realname' => 'jacl2_rights',
    'pk' => 
    array (
      0 => 'id_aclsbj',
      1 => 'id_aclgrp',
      2 => 'id_aclres',
    ),
    'fields' => 
    array (
      0 => 'id_aclsbj',
      1 => 'id_aclgrp',
      2 => 'id_aclres',
      3 => 'canceled',
    ),
  ),
);
   protected $_primaryTable = 'r';
   protected $_selectClause='SELECT r.id_aclsbj as id_aclsbj, r.id_aclgrp as id_aclgrp, r.id_aclres as id_aclres, r.canceled as canceled';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jacl2db_Jx_jacl2rights_Jx_sqlite3';
   protected $_daoSelector = 'jacl2db~jacl2rights';
   public static $_properties = array (
  'id_aclsbj' => 
  array (
    'name' => 'id_aclsbj',
    'fieldName' => 'id_aclsbj',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'r',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 100,
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
    'table' => 'r',
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
  'id_aclres' => 
  array (
    'name' => 'id_aclres',
    'fieldName' => 'id_aclres',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'r',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 100,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'canceled' => 
  array (
    'name' => 'canceled',
    'fieldName' => 'canceled',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'tinyint',
    'unifiedType' => 'integer',
    'table' => 'r',
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
);
   public static $_pkFields = array('id_aclsbj','id_aclgrp','id_aclres');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('jacl2_rights').' AS r';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  r.id_aclsbj'.' = '.$this->_conn->quote($id_aclsbj).' AND r.id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).' AND r.id_aclres'.' = '.$this->_conn->quote($id_aclres).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id_aclsbj'.' = '.$this->_conn->quote($id_aclsbj).' AND id_aclgrp'.' = '.$this->_conn->quote($id_aclgrp).' AND id_aclres'.' = '.$this->_conn->quote($id_aclres).'';
}
public function insert ($record){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('jacl2_rights').' (
id_aclsbj,id_aclgrp,id_aclres,canceled
) VALUES (
'.($record->id_aclsbj === null ? 'NULL' : $this->_conn->quote2($record->id_aclsbj,false)).', '.($record->id_aclgrp === null ? 'NULL' : $this->_conn->quote2($record->id_aclgrp,false)).', '.($record->id_aclres === null ? 'NULL' : $this->_conn->quote2($record->id_aclres,false)).', '.($record->canceled === null ? 'NULL' : intval($record->canceled)).'
)';
   $result = $this->_conn->exec ($query);
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('jacl2_rights').' SET 
 canceled= '.($record->canceled === null ? 'NULL' : intval($record->canceled)).'
 where  id_aclsbj'.' = '.$this->_conn->quote($record->id_aclsbj).' AND id_aclgrp'.' = '.$this->_conn->quote($record->id_aclgrp).' AND id_aclres'.' = '.$this->_conn->quote($record->id_aclres).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function getRight ($subject, $groups){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclsbj '.' = '.$this->_conn->quote($subject).' AND r.id_aclres = \'-\' AND r.id_aclgrp IN ('.implode(',', array_map( array($this, '_callbackQuote'), is_array($groups)?$groups:array($groups))).')';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getRightsByGroups ($groups){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclres = \'-\' AND r.id_aclgrp IN ('.implode(',', array_map( array($this, '_callbackQuote'), is_array($groups)?$groups:array($groups))).')';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getRightsByGroup ($group){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclres = \'-\' AND r.id_aclgrp '.' = '.$this->_conn->quote($group).'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getResByRightByGroup ($group, $subject){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclsbj '.' = '.$this->_conn->quote($subject).' AND r.id_aclgrp '.' = '.$this->_conn->quote($group).' AND r.id_aclres <> \'-\'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getRightWithRes ($subject, $groups, $res){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclsbj '.' = '.$this->_conn->quote($subject).' AND r.id_aclres '.' = '.$this->_conn->quote($res).' AND r.id_aclgrp IN ('.implode(',', array_map( array($this, '_callbackQuote'), is_array($groups)?$groups:array($groups))).') ORDER BY r.canceled desc';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getRightsHavingRes ($group){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclres <> \'-\' AND r.id_aclgrp '.' = '.$this->_conn->quote($group).' ORDER BY r.id_aclsbj asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function getAnonymousRight ($subject){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclsbj '.' = '.$this->_conn->quote($subject).' AND r.id_aclres = \'-\' AND r.id_aclgrp = \'__anonymous\'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getAnonymousRightWithRes ($subject, $res){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclsbj '.' = '.$this->_conn->quote($subject).' AND r.id_aclres '.' = '.$this->_conn->quote($res).' AND r.id_aclgrp = \'__anonymous\'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getAllAnonymousRights (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  r.id_aclres = \'-\' AND r.id_aclgrp = \'__anonymous\'';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function deleteByGroup ($group){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_rights').' ';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).'';
    return $this->_conn->exec ($__query);
}
 function deleteBySubjRes ($subject, $res){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_rights').' ';
$__query .=' WHERE  id_aclsbj '.' = '.$this->_conn->quote($subject).' AND id_aclres '.' = '.$this->_conn->quote($res).'';
    return $this->_conn->exec ($__query);
}
 function deleteBySubject ($subject){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_rights').' ';
$__query .=' WHERE  id_aclsbj '.' = '.$this->_conn->quote($subject).'';
    return $this->_conn->exec ($__query);
}
 function deleteByGroupAndSubjects ($group, $subjects){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_rights').' ';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).' AND id_aclsbj IN ('.implode(',', array_map( array($this, '_callbackQuote'), is_array($subjects)?$subjects:array($subjects))).') AND id_aclres = \'-\'';
    return $this->_conn->exec ($__query);
}
 function deleteRightsOnResource ($group, $subjects){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jacl2_rights').' ';
$__query .=' WHERE  id_aclgrp '.' = '.$this->_conn->quote($group).' AND id_aclsbj IN ('.implode(',', array_map( array($this, '_callbackQuote'), is_array($subjects)?$subjects:array($subjects))).') AND id_aclres <> \'-\'';
    return $this->_conn->exec ($__query);
}

}
 return true; }