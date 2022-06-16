<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_attributeLayers.tpl') > 1616160726){ return false;
} else {
function template_meta_f9981a64e9330687021e4580c0b4ce99($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_f9981a64e9330687021e4580c0b4ce99($t){
?><div class="tabbable">
  <div class="tab-content" id="attribute-table-container">

    <div class="tab-pane active attribute-content bottom-content" id="attribute-summary" >

      <div id="attribute-layer-list"></div>

      <b><?php echo jLocale::get('view~map.attributeLayers.options.title'); ?></b>
      <?php  $formfull = $t->_vars['form'];
    $formfullBuilder = $formfull->getBuilder( 'htmlbootstrap');
    $formfullBuilder->setOptions(array());
    $formfullBuilder->setAction( 'view~default:index', array());
    $formfullBuilder->outputHeader();
    $formfullBuilder->outputAllControls();
    $formfullBuilder->outputFooter();?>

    </div>


  </div>

  <ul id="attributeLayers-tabs" class="nav nav-tabs">
    <li id="nav-tab-attribute-summary" class="active"><a href="#attribute-summary" data-toggle="tab"><?php echo jLocale::get('view~map.attributeLayers.toolbar.title'); ?></a></li>
  </ul>

</div>



<?php 
}
return true;}
