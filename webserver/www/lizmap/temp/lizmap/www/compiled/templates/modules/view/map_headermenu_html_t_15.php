<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_headermenu.tpl') > 1616160726){ return false;
} else {
function template_meta_613f2fd98508b61bacff6f99e9e309da($t){
$t->meta('lizmap~user_menu');

}
function template_613f2fd98508b61bacff6f99e9e309da($t){
?><div id="auth" class="navbar-inner">
  <div class="pull-right">
    <form id="nominatim-search" class="navbar-search dropdown">
   <button id="header-clear" class="btn-locate-clear btn btn-mini btn-link icon" type="button"></button>
      <input id="search-query" type="text" class="search-query" placeholder="<?php echo jLocale::get('view~map.search.nominatim.placeholder'); ?>"></input>
      <span class="search-icon">
        <button class="icon nav-search" type="submit" tabindex="-1">
          <span><?php echo jLocale::get('view~map.search.nominatim.button'); ?></span>
        </button>
      </span>
    </form>

    <ul class="nav">
      <?php $t->display('lizmap~user_menu');?>

    </ul>
  </div>
</div>
<?php 
}
return true;}
