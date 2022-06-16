<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_locate.tpl') > 1616160726){ return false;
} else {
function template_meta_ec8d1896e0823974518fa6bb8b1adaf3($t){

}
function template_ec8d1896e0823974518fa6bb8b1adaf3($t){
?><div class="locate">
  <h3>
    <span class="title">
      <button id="locate-close" class="btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button>
      <button id="locate-clear" class="btn-locate-clear btn btn-mini btn-link" type="button"></button>
      <span class="icon"></span>
      <span class="text">&nbsp;<?php echo jLocale::get('view~map.locatemenu.title'); ?>&nbsp;</span>
    </span>
  </h3>
  <div class="menu-content">
  </div>
</div>
<?php 
}
return true;}
