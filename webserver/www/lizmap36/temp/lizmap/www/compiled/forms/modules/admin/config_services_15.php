<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\forms/config_services.form.xml') > 1696403627){ return false;
}else{

class cForm_admin_Jx_config_services extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlInput('appName');
$ctrl->required=true;
$ctrl->defaultValue='Lizmap';
$ctrl->label=jLocale::get('admin~admin.form.admin_services.appName.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('onlyMaps');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.onlyMaps.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('off'=>jLocale::get('admin~admin.form.admin_services.off.label'),'on'=>jLocale::get('admin~admin.form.admin_services.on.label'),);
$ctrl->defaultValue=array (
  0 => 'off',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('projectSwitcher');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.projectSwitcher.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('off'=>jLocale::get('admin~admin.form.admin_services.off.label'),'on'=>jLocale::get('admin~admin.form.admin_services.on.label'),);
$ctrl->defaultValue=array (
  0 => 'off',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('googleAnalyticsID');
$ctrl->datatype->addFacet('pattern','/^UA-\d+-\d+$/');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.googleAnalyticsID.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.googleAnalyticsID.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('rootRepositories');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.rootRepositories.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.rootRepositories.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('defaultRepository');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.defaultRepository.label');
jClasses::inc('admin~listRepositoryDatasource');
$datasource = new listRepositoryDatasource($this->id());
if ($datasource instanceof jIFormsDatasource){$ctrl->datasource=$datasource;
}
else{$ctrl->datasource=new jFormsStaticDatasource();}
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('defaultProject');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.defaultProject.label');
jClasses::inc('admin~listProjectDatasource');
$datasource = new listProjectDatasource($this->id());
if ($datasource instanceof jIFormsDatasource){$ctrl->datasource=$datasource;
if($datasource instanceof jIFormsDynamicDatasource) $datasource->setCriteriaControls(array('defaultRepository'));
}
else{$ctrl->datasource=new jFormsStaticDatasource();}
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('qgisServerVersion');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.qgisServerVersion.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('2.14'=>jLocale::get('admin~admin.form.admin_services.qgisServerVersion.214.label'),'2.18'=>jLocale::get('admin~admin.form.admin_services.qgisServerVersion.218.label'),'3.0'=>jLocale::get('admin~admin.form.admin_services.qgisServerVersion.300.label'),);
$ctrl->defaultValue=array (
  0 => '3.0',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('wmsServerURL');
$ctrl->required=true;
$ctrl->defaultValue='http://127.0.0.1/cgi-bin/qgis_mapserv.fcgi';
$ctrl->label=jLocale::get('admin~admin.form.admin_services.wmsServerURL.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('wmsPublicUrlList');
$ctrl->defaultValue='';
$ctrl->label=jLocale::get('admin~admin.form.admin_services.wmsPublicUrlList.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('relativeWMSPath');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.relativeWMSPath.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('0'=>jLocale::get('admin~admin.form.admin_services.no.label'),'1'=>jLocale::get('admin~admin.form.admin_services.yes.label'),);
$ctrl->defaultValue=array (
  0 => '0',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('wmsMaxWidth');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->defaultValue='3000';
$ctrl->datatype->addFacet('minValue',256);
$ctrl->datatype->addFacet('maxValue',256000);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.wmsMaxWidth.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('wmsMaxHeight');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->defaultValue='3000';
$ctrl->datatype->addFacet('minValue',256);
$ctrl->datatype->addFacet('maxValue',256000);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.wmsMaxHeight.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('lizmapPluginAPIURL');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.lizmapPluginAPIURL.label');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('cacheStorageType');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheStorageType.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('sqlite'=>jLocale::get('admin~admin.form.admin_services.cacheStorageType.sqlite.label'),'file'=>jLocale::get('admin~admin.form.admin_services.cacheStorageType.file.label'),'redis'=>jLocale::get('admin~admin.form.admin_services.cacheStorageType.redis.label'),);
$ctrl->defaultValue=array (
  0 => 'sqlite',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheRootDirectory');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheRootDirectory.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheRootDirectory.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheRedisHost');
$ctrl->defaultValue='localhost';
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheRedisHost.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheRedisHost.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheRedisPort');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->defaultValue='6379';
$ctrl->datatype->addFacet('minValue',1);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheRedisPort.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheRedisPort.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheRedisDb');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->defaultValue='0';
$ctrl->datatype->addFacet('minValue',0);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheRedisDb.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheRedisDb.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheRedisKeyPrefix');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheRedisKeyPrefix.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheRedisKeyPrefix.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('cacheExpiration');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->required=true;
$ctrl->defaultValue='2592000';
$ctrl->label=jLocale::get('admin~admin.form.admin_services.cacheExpiration.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.cacheExpiration.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('debugMode');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.debugMode.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('0'=>jLocale::get('admin~admin.form.admin_services.debugMode.0.label'),'1'=>jLocale::get('admin~admin.form.admin_services.debugMode.1.label'),);
$ctrl->defaultValue=array (
  0 => '0',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlGroup('requestProxyEnabled');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxy.label');
$ctrl->hasCheckbox=true;
$ctrl->valueLabelOnCheck=jLocale::get('admin~admin.form.admin_services.requestProxy.enabled');
$ctrl->valueOnCheck='1';
$ctrl->valueLabelOnUncheck=jLocale::get('admin~admin.form.admin_services.requestProxy.disabled');
$ctrl->valueOnUncheck='0';
$topctrl = $ctrl;
$ctrl= new jFormsControlInput('requestProxyHost');
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyHost.label');
$topctrl->addChildControl($ctrl);
$ctrl= new jFormsControlInput('requestProxyPort');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->required=true;
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyPort.label');
$topctrl->addChildControl($ctrl);
$ctrl= new jFormsControlRadiobuttons('requestProxyType');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyType.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('http'=>'http','socks5'=>'socks5',);
$topctrl->addChildControl($ctrl);
$ctrl= new jFormsControlInput('requestProxyUser');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyUser.label');
$topctrl->addChildControl($ctrl);
$ctrl= new jFormsControlSecret('requestProxyPassword');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyPassword.label');
$topctrl->addChildControl($ctrl);
$ctrl= new jFormsControlInput('requestProxyNotForDomain');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.requestProxyNotForDomain.label');
$topctrl->addChildControl($ctrl);
$ctrl = $topctrl;
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('adminSenderEmail');
$ctrl->datatype= new jDatatypeemail();
$ctrl->label=jLocale::get('admin~admin.form.admin_services.adminSenderEmail.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.adminSenderEmail.help');
$ctrl->alertRequired=jLocale::get('admin~admin.form.admin_services.adminSenderEmail.error.required');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('adminSenderName');
$ctrl->datatype->addFacet('maxLength',250);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.adminSenderName.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.adminSenderName.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlMenulist('allowUserAccountRequests');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.allowUserAccountRequests.label');
$ctrl->datasource= new jFormsStaticDatasource();
$ctrl->datasource->data = array('off'=>jLocale::get('admin~admin.form.admin_services.off.label'),'on'=>jLocale::get('admin~admin.form.admin_services.on.label'),);
$ctrl->defaultValue=array (
  0 => 'off',
);
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('adminContactEmail');
$ctrl->datatype= new jDatatypeemail();
$ctrl->label=jLocale::get('admin~admin.form.admin_services.adminContactEmail.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.adminContactEmail.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlInput('uploadedImageMaxWidthHeight');
$ctrl->datatype= new jDatatypeinteger();
$ctrl->datatype->addFacet('minValue',480);
$ctrl->datatype->addFacet('maxValue',3840);
$ctrl->label=jLocale::get('admin~admin.form.admin_services.uploadedImageMaxWidthHeight.label');
$ctrl->help=jLocale::get('admin~admin.form.admin_services.uploadedImageMaxWidthHeight.help');
$this->addControl($ctrl);
$ctrl= new jFormsControlSubmit('_submit');
$ctrl->label=jLocale::get('admin~admin.form.admin_services.submit.label');
$ctrl->datasource= new jFormsStaticDatasource();
$this->addControl($ctrl);
  }
}
 return true;}