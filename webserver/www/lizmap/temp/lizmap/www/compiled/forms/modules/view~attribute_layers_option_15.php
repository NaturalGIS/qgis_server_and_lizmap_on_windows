<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&

filemtime('C:\webserver\www\lizmap\lizmap/modules/view/forms/attribute_layers_option.form.xml') > 1616160726){ return false;
}else{

class cForm_view_Jx_attribute_layers_option extends jFormsBase {
 public function __construct($sel, &$container, $reset = false){
          parent::__construct($sel, $container, $reset);
$ctrl= new jFormsControlhidden('liz_project');
$this->addControl($ctrl);
$ctrl= new jFormsControlcheckbox('cascade');
$ctrl->defaultValue='1';
$ctrl->label=jLocale::get('view~map.attributeLayers.options.input.cascade.label');
$this->addControl($ctrl);
  }
}
 return true;}