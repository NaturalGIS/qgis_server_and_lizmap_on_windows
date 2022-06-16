<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_geolocation.tpl') > 1648478290){ return false;
} else {
function template_meta_7594b35a9b08e369ca44b2d338b543f0($t){

}
function template_7594b35a9b08e369ca44b2d338b543f0($t){
?><div class="geolocation">
  <h3>
    <span class="title">
      <button class="btn-geolocation-close btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button>
      <span class="icon"></span>
      <span class="text">&nbsp;<?php echo jLocale::get('view~map.geolocate.toolbar.title'); ?>&nbsp;</span>
    </span>
  </h3>
  <lizmap-geolocation></lizmap-geolocation>
</div>
<?php 
}
return true;}
