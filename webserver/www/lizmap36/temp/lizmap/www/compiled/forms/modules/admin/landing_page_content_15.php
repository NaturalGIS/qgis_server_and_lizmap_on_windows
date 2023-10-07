<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\forms/landing_page_content.form.xml') > 1696349194){ return false;
}else{

class cForm_admin_Jx_landing_page_content extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlHtmlEditor('HTMLContent');
$ctrl->label='';
$ctrl->config='ckfullandmedia';
$this->addControl($ctrl);
$ctrl= new jFormsControlSubmit('_submit');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.submit.label');
$ctrl->datasource= new jFormsStaticDatasource();
$this->addControl($ctrl);
  }
}
 return true;}