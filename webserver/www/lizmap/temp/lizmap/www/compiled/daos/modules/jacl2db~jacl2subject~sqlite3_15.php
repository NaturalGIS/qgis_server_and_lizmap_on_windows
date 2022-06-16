<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lib/jelix-modules/jacl2db/daos/jacl2subject.dao.xml') > 1616160725)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_jacl2db_Jx_jacl2subject_Jx_sqlite3 extends jDaoRecordBase {
 public $id_aclsbj;
 public $label_key;
 public $id_aclsbjgrp;
 public $label_group_key;
   public function getSelector() { return "jacl2db~jacl2subject"; }
   public function getProperties() { return cDao_jacl2db_Jx_jacl2subject_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_jacl2db_Jx_jacl2subject_Jx_sqlite3::$_pkFields; }
}

class cDao_jacl2db_Jx_jacl2subject_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'sbj' => 
  array (
    'name' => 'sbj',
    'realname' => 'jacl2_subject',
    'pk' => 
    array (
      0 => 'id_aclsbj',
    ),
    'fields' => 
    array (
      0 => 'id_aclsbj',
      1 => 'label_key',
      2 => 'id_aclsbjgrp',
    ),
  ),
  'grpsbj' => 
  array (
    'name' => 'grpsbj',
    'realname' => 'jacl2_subject_group',
    'pk' => 
    array (
      0 => 'id_aclsbjgrp',
    ),
    'fk' => 
    array (
      0 => 'id_aclsbjgrp',
    ),
    'fields' => 
    array (
      0 => 'label_group_key',
    ),
  ),
);
   protected $_primaryTable = 'sbj';
   protected $_selectClause='SELECT sbj.id_aclsbj as id_aclsbj, sbj.label_key as label_key, sbj.id_aclsbjgrp as id_aclsbjgrp, grpsbj.label_key as label_group_key';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_jacl2db_Jx_jacl2subject_Jx_sqlite3';
   protected $_daoSelector = 'jacl2db~jacl2subject';
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
    'table' => 'sbj',
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
  'label_key' => 
  array (
    'name' => 'label_key',
    'fieldName' => 'label_key',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'sbj',
    'updatePattern' => '%s',
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
  'id_aclsbjgrp' => 
  array (
    'name' => 'id_aclsbjgrp',
    'fieldName' => 'id_aclsbjgrp',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => true,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'sbj',
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
  'label_group_key' => 
  array (
    'name' => 'label_group_key',
    'fieldName' => 'label_key',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'grpsbj',
    'updatePattern' => '',
    'insertPattern' => '',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => NULL,
    'minlength' => NULL,
    'ofPrimaryTable' => false,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
);
   public static $_pkFields = array('id_aclsbj');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('jacl2_subject').' AS sbj LEFT JOIN '.$this->_conn->prefixTable('jacl2_subject_group').' AS grpsbj ON ( sbj.id_aclsbjgrp=grpsbj.id_aclsbjgrp)';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  sbj.id_aclsbj'.' = '.$this->_conn->quote($id_aclsbj).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  id_aclsbj'.' = '.$this->_conn->quote($id_aclsbj).'';
}
public function insert ($record){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('jacl2_subject').' (
id_aclsbj,label_key,id_aclsbjgrp
) VALUES (
'.($record->id_aclsbj === null ? 'NULL' : $this->_conn->quote2($record->id_aclsbj,false)).', '.($record->label_key === null ? 'NULL' : $this->_conn->quote2($record->label_key,false)).', '.($record->id_aclsbjgrp === null ? 'NULL' : $this->_conn->quote2($record->id_aclsbjgrp,false)).'
)';
   $result = $this->_conn->exec ($query);
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('jacl2_subject').' SET 
 label_key= '.($record->label_key === null ? 'NULL' : $this->_conn->quote2($record->label_key,false)).', id_aclsbjgrp= '.($record->id_aclsbjgrp === null ? 'NULL' : $this->_conn->quote2($record->id_aclsbjgrp,false)).'
 where  id_aclsbj'.' = '.$this->_conn->quote($record->id_aclsbj).'
';
   $result = $this->_conn->exec ($query);
   return $result;
 }
 function findAllSubject (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY sbj.id_aclsbjgrp asc, sbj.id_aclsbj asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function removeSubjectFromGroup ($id_aclsbjgrp){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jacl2_subject').' SET 
 id_aclsbjgrp= NULL';
$__query .=' WHERE  id_aclsbjgrp '.($id_aclsbjgrp === null ? 'IS NULL' : ' = '.$this->_conn->quote2($id_aclsbjgrp,false)).'';
    return $this->_conn->exec ($__query);
}
 function replaceSubjectGroup ($old_id_aclsbjgrp, $new_id_aclsbjgrp){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jacl2_subject').' SET 
 id_aclsbjgrp= '.($new_id_aclsbjgrp === null ? 'NULL' : $this->_conn->quote2($new_id_aclsbjgrp,false)).'';
$__query .=' WHERE  id_aclsbjgrp '.($old_id_aclsbjgrp === null ? 'IS NULL' : ' = '.$this->_conn->quote2($old_id_aclsbjgrp,false)).'';
    return $this->_conn->exec ($__query);
}

}
 return true; }