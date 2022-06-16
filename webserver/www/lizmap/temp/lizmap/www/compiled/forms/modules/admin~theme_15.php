<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/forms/theme.form.xml') > 1616160726){ return false;
}else{

class cForm_admin_Jx_theme extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlupload('headerLogo');
$ctrl->label=jLocale::get('admin.form.admin_theme.headerLogo.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerLogo.help');
$ctrl->maxsize=200000;
$ctrl->mimetype=array (
  0 => 'image/jpeg',
  1 => 'image/pjpeg',
  2 => 'image/png',
  3 => 'image/gif',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('headerLogoWidth');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.headerLogoWidth.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerLogoWidth.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlupload('headerBackgroundImage');
$ctrl->label=jLocale::get('admin.form.admin_theme.headerBackgroundImage.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerBackgroundImage.help');
$ctrl->maxsize=400000;
$ctrl->mimetype=array (
  0 => 'image/jpeg',
  1 => 'image/pjpeg',
  2 => 'image/png',
  3 => 'image/gif',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('headerBackgroundColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.headerBackgroundColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerBackgroundColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('headerTitleColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.headerTitleColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerTitleColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('headerSubtitleColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.headerSubtitleColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.headerSubtitleColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('menuBackgroundColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.menuBackgroundColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.menuBackgroundColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('dockBackgroundColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.dockBackgroundColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.dockBackgroundColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlinput('navbarColor');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin.form.admin_theme.navbarColor.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.navbarColor.help');
$this->addControl($ctrl);
$ctrl= new jFormsControltextarea('additionalCss');
$ctrl->label=jLocale::get('admin.form.admin_theme.additionalCss.label');
$ctrl->help=jLocale::get('admin.form.admin_theme.additionalCss.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlsubmit('_submit');
$ctrl->label=jLocale::get('admin.form.admin_services.submit.label');
$ctrl->datasource= new jFormsStaticDatasource();
$this->addControl($ctrl);
  }
}
 return true;}