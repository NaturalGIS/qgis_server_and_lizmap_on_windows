<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_measure.tpl') > 1648478290){ return false;
} else {
function template_meta_d1fe8df93ac3af6a91e40a2426617dd7($t){

}
function template_d1fe8df93ac3af6a91e40a2426617dd7($t){
?><div class="measure">
  <h3><span class="title"><button id="measure-stop" class="btn btn-stop btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button><span class="icon"></span>&nbsp;<span class="text"><?php echo jLocale::get('view~map.measure.toolbar.title'); ?></span></span></h3>
  <div class="menu-content">
    <select id="measure-type">
      <option value="length"><?php echo jLocale::get('view~map.measure.toolbar.length'); ?></option>
      <option value="area"><?php echo jLocale::get('view~map.measure.toolbar.area'); ?></option>
      <option value="perimeter"><?php echo jLocale::get('view~map.measure.toolbar.perimeter'); ?></option>
      <option value="angle"><?php echo jLocale::get('view~map.measure.toolbar.angle'); ?></option>
    </select>
  </div>
</div>
<?php 
}
return true;}
