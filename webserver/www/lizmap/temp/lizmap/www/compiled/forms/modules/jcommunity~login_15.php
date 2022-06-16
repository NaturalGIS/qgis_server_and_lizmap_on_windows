<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap\lizmap/vendor/jelix/jcommunity-module/modules/jcommunity/forms/login.form.xml') > 1641481291){ return false;
}else{

class cForm_jcommunity_Jx_login extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlinput('auth_login');
$ctrl->required=true;
$ctrl->label=jLocale::get('jcommunity~account.form.login');
$ctrl->size=8;
$this->addControl($ctrl);
$ctrl= new jFormsControlsecret('auth_password');
$ctrl->datatype->addFacet('maxLength',120);
$ctrl->required=true;
$ctrl->label=jLocale::get('jcommunity~account.form.password');
$ctrl->size=8;
$this->addControl($ctrl);
$ctrl= new jFormsControlcheckbox('auth_remember_me');
$ctrl->label=jLocale::get('jcommunity~login.form.remember.me');
$ctrl->valueOnCheck='1';
$ctrl->valueOnUncheck='0';
$this->addControl($ctrl);
$ctrl= new jFormsControlsubmit('auth_submit');
$ctrl->label=jLocale::get('jcommunity~login.form.submit');
$ctrl->datasource= new jFormsStaticDatasource();
$this->addControl($ctrl);
  }
}
 return true;}