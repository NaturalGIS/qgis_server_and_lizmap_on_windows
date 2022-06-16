<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/forms/config_section.form.xml') > 1616160726){ return false;
}else{

class cForm_admin_Jx_config_section extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlhidden('new');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('repository');
$ctrl->datatype->addFacet('pattern','/^[a-z0-9]+$/');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_section.repository.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlsubmit('_submit');
$ctrl->label=jLocale::get('admin~admin.form.admin_section.submit.label');
$ctrl->datasource= new jFormsStaticDatasource();
$this->addControl($ctrl);
  }
}
 return true;}