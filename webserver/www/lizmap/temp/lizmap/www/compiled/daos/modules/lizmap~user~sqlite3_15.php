<?php 
if (jApp::config()->compilation['checkCacheFiletime']&&(

 filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/daos/user.dao.xml') > 1616160726)){ return false;
}
else {
 require_once ( JELIX_LIB_PATH .'dao/jDaoRecordBase.class.php');
 require_once ( JELIX_LIB_PATH .'dao/jDaoFactoryBase.class.php');

class cDaoRecord_lizmap_Jx_user_Jx_sqlite3 extends jDaoRecordBase {
 public $login;
 public $email;
 public $password;
 public $firstname;
 public $lastname;
 public $organization;
 public $phonenumber;
 public $street;
 public $postcode;
 public $city;
 public $country;
 public $comment;
 public $status;
 public $keyactivate;
 public $request_date;
 public $create_date;
   public function getSelector() { return "lizmap~user"; }
   public function getProperties() { return cDao_lizmap_Jx_user_Jx_sqlite3::$_properties; }
   public function getPrimaryKeyNames() { return cDao_lizmap_Jx_user_Jx_sqlite3::$_pkFields; }
}

class cDao_lizmap_Jx_user_Jx_sqlite3 extends jDaoFactoryBase {
   protected $_tables = array (
  'usr' => 
  array (
    'name' => 'usr',
    'realname' => 'jlx_user',
    'pk' => 
    array (
      0 => 'usr_login',
    ),
    'fields' => 
    array (
      0 => 'login',
      1 => 'email',
      2 => 'password',
      3 => 'firstname',
      4 => 'lastname',
      5 => 'organization',
      6 => 'phonenumber',
      7 => 'street',
      8 => 'postcode',
      9 => 'city',
      10 => 'country',
      11 => 'comment',
      12 => 'status',
      13 => 'keyactivate',
      14 => 'request_date',
      15 => 'create_date',
    ),
  ),
);
   protected $_primaryTable = 'usr';
   protected $_selectClause='SELECT usr.usr_login as login, usr.usr_email as email, usr.usr_password as password, usr.firstname as firstname, usr.lastname as lastname, usr.organization as organization, usr.phonenumber as phonenumber, usr.street as street, usr.postcode as postcode, usr.city as city, usr.country as country, usr.comment as comment, usr.status as status, usr.keyactivate as keyactivate, usr.request_date as request_date, usr.create_date as create_date';
   protected $_fromClause;
   protected $_whereClause='';
   protected $_DaoRecordClassName='cDaoRecord_lizmap_Jx_user_Jx_sqlite3';
   protected $_daoSelector = 'lizmap~user';
   public static $_properties = array (
  'login' => 
  array (
    'name' => 'login',
    'fieldName' => 'usr_login',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => true,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'email' => 
  array (
    'name' => 'email',
    'fieldName' => 'usr_email',
    'regExp' => NULL,
    'required' => true,
    'requiredInConditions' => true,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 255,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'password' => 
  array (
    'name' => 'password',
    'fieldName' => 'usr_password',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
    'updatePattern' => '',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 120,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'firstname' => 
  array (
    'name' => 'firstname',
    'fieldName' => 'firstname',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'lastname' => 
  array (
    'name' => 'lastname',
    'fieldName' => 'lastname',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'organization' => 
  array (
    'name' => 'organization',
    'fieldName' => 'organization',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'phonenumber' => 
  array (
    'name' => 'phonenumber',
    'fieldName' => 'phonenumber',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 20,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'street' => 
  array (
    'name' => 'street',
    'fieldName' => 'street',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'postcode' => 
  array (
    'name' => 'postcode',
    'fieldName' => 'postcode',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 10,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'city' => 
  array (
    'name' => 'city',
    'fieldName' => 'city',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'country' => 
  array (
    'name' => 'country',
    'fieldName' => 'country',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'comment' => 
  array (
    'name' => 'comment',
    'fieldName' => 'comment',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'text',
    'unifiedType' => 'text',
    'table' => 'usr',
    'updatePattern' => '%s',
    'insertPattern' => '%s',
    'selectPattern' => '%s',
    'sequenceName' => '',
    'maxlength' => 300,
    'minlength' => NULL,
    'ofPrimaryTable' => true,
    'defaultValue' => NULL,
    'autoIncrement' => false,
    'comment' => '',
  ),
  'status' => 
  array (
    'name' => 'status',
    'fieldName' => 'status',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'integer',
    'unifiedType' => 'integer',
    'table' => 'usr',
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
  'keyactivate' => 
  array (
    'name' => 'keyactivate',
    'fieldName' => 'keyactivate',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'string',
    'unifiedType' => 'varchar',
    'table' => 'usr',
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
  'request_date' => 
  array (
    'name' => 'request_date',
    'fieldName' => 'request_date',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'datetime',
    'unifiedType' => 'datetime',
    'table' => 'usr',
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
  'create_date' => 
  array (
    'name' => 'create_date',
    'fieldName' => 'create_date',
    'regExp' => NULL,
    'required' => false,
    'requiredInConditions' => false,
    'isPK' => false,
    'isFK' => false,
    'datatype' => 'datetime',
    'unifiedType' => 'datetime',
    'table' => 'usr',
    'updatePattern' => '',
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
);
   public static $_pkFields = array('login');
 
public function __construct($conn){
   parent::__construct($conn);
   $this->_fromClause = ' FROM '.$this->_conn->prefixTable('jlx_user').' AS usr';
}
 
 protected function _getPkWhereClauseForSelect($pk){
   extract($pk);
 return ' WHERE  usr.usr_login'.' = '.$this->_conn->quote($login).'';
}
 
protected function _getPkWhereClauseForNonSelect($pk){
   extract($pk);
   return ' where  usr_login'.' = '.$this->_conn->quote($login).'';
}
public function insert ($record){
    $query = 'INSERT INTO '.$this->_conn->prefixTable('jlx_user').' (
usr_login,usr_email,usr_password,firstname,lastname,organization,phonenumber,street,postcode,city,country,comment,status,keyactivate,request_date,create_date
) VALUES (
'.($record->login === null ? 'NULL' : $this->_conn->quote2($record->login,false)).', '.($record->email === null ? 'NULL' : $this->_conn->quote2($record->email,false)).', '.($record->password === null ? 'NULL' : $this->_conn->quote2($record->password,false)).', '.($record->firstname === null ? 'NULL' : $this->_conn->quote2($record->firstname,false)).', '.($record->lastname === null ? 'NULL' : $this->_conn->quote2($record->lastname,false)).', '.($record->organization === null ? 'NULL' : $this->_conn->quote2($record->organization,false)).', '.($record->phonenumber === null ? 'NULL' : $this->_conn->quote2($record->phonenumber,false)).', '.($record->street === null ? 'NULL' : $this->_conn->quote2($record->street,false)).', '.($record->postcode === null ? 'NULL' : $this->_conn->quote2($record->postcode,false)).', '.($record->city === null ? 'NULL' : $this->_conn->quote2($record->city,false)).', '.($record->country === null ? 'NULL' : $this->_conn->quote2($record->country,false)).', '.($record->comment === null ? 'NULL' : $this->_conn->quote2($record->comment,false)).', '.($record->status === null ? 'NULL' : intval($record->status)).', '.($record->keyactivate === null ? 'NULL' : $this->_conn->quote2($record->keyactivate,false)).', '.($record->request_date === null ? 'NULL' : $this->_conn->quote2($record->request_date,false)).', datetime(\'now\', \'localtime\')
)';
   $result = $this->_conn->exec ($query);
  $query ='SELECT create_date as create_date FROM '.$this->_conn->prefixTable('jlx_user').' WHERE  usr_login'.' = '.$this->_conn->quote($record->login).'';
  $rs  =  $this->_conn->query ($query);
  $newrecord =  $rs->fetch ();
  $record->create_date = $newrecord->create_date;
    return $result;
}
public function update ($record){
   $query = 'UPDATE '.$this->_conn->prefixTable('jlx_user').' SET 
 usr_email= '.($record->email === null ? 'NULL' : $this->_conn->quote2($record->email,false)).', firstname= '.($record->firstname === null ? 'NULL' : $this->_conn->quote2($record->firstname,false)).', lastname= '.($record->lastname === null ? 'NULL' : $this->_conn->quote2($record->lastname,false)).', organization= '.($record->organization === null ? 'NULL' : $this->_conn->quote2($record->organization,false)).', phonenumber= '.($record->phonenumber === null ? 'NULL' : $this->_conn->quote2($record->phonenumber,false)).', street= '.($record->street === null ? 'NULL' : $this->_conn->quote2($record->street,false)).', postcode= '.($record->postcode === null ? 'NULL' : $this->_conn->quote2($record->postcode,false)).', city= '.($record->city === null ? 'NULL' : $this->_conn->quote2($record->city,false)).', country= '.($record->country === null ? 'NULL' : $this->_conn->quote2($record->country,false)).', comment= '.($record->comment === null ? 'NULL' : $this->_conn->quote2($record->comment,false)).', status= '.($record->status === null ? 'NULL' : intval($record->status)).', keyactivate= '.($record->keyactivate === null ? 'NULL' : $this->_conn->quote2($record->keyactivate,false)).', request_date= '.($record->request_date === null ? 'NULL' : $this->_conn->quote2($record->request_date,false)).'
 where  usr_login'.' = '.$this->_conn->quote($record->login).'
';
   $result = $this->_conn->exec ($query);
  $query ='SELECT usr_password as password, create_date as create_date FROM '.$this->_conn->prefixTable('jlx_user').' WHERE  usr_login'.' = '.$this->_conn->quote($record->login).'';
  $rs  =  $this->_conn->query ($query, jDbConnection::FETCH_INTO, $record);
  $record =  $rs->fetch ();
   return $result;
 }
 function getByLoginPassword ($login, $password){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  usr.usr_login '.' = '.$this->_conn->quote($login).' AND usr.usr_password '.($password === null ? 'IS NULL' : ' = '.$this->_conn->quote2($password,false)).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function getByLogin ($login){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  usr.usr_login '.' = '.$this->_conn->quote($login).'';
    $__rs = $this->_conn->limitQuery($__query,0,1);
    $this->finishInitResultSet($__rs);
    return $__rs->fetch();
}
 function updatePassword ($login, $password){
    $__query = 'UPDATE '.$this->_conn->prefixTable('jlx_user').' SET 
 usr_password= '.($password === null ? 'NULL' : $this->_conn->quote2($password,false)).'';
$__query .=' WHERE  usr_login '.' = '.$this->_conn->quote($login).'';
    return $this->_conn->exec ($__query);
}
 function deleteByLogin ($login){
    $__query = 'DELETE FROM '.$this->_conn->prefixTable('jlx_user').' ';
$__query .=' WHERE  usr_login '.' = '.$this->_conn->quote($login).'';
    return $this->_conn->exec ($__query);
}
 function findByLogin ($pattern){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  usr.usr_login '.' LIKE '.$this->_conn->quote($pattern).' ORDER BY usr.usr_login asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}
 function findAll (){
    $__query =  $this->_selectClause.$this->_fromClause.$this->_whereClause;
$__query .=' WHERE  1=1  ORDER BY usr.usr_login asc';
    $__rs = $this->_conn->query($__query);
    $this->finishInitResultSet($__rs);
    return $__rs;
}

}
 return true; }