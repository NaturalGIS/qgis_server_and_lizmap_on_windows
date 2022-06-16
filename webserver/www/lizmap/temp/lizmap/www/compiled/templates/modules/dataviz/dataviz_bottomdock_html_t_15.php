<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/dataviz/templates/dataviz_bottomdock.tpl') > 1648478290){ return false;
} else {
function template_meta_8475d68c1d0540471ad0fb5b60054328($t){

}
function template_8475d68c1d0540471ad0fb5b60054328($t){
?><div class="tabbable">
  <div class="tab-content" id="dataviz-container">

    <div class="tab-pane active bottom-content" id="dataviz-main" >

        <div id="dataviz-content" class="<?php echo $t->_vars['theme']; ?>">
        </div>

    </div>

  </div>

  <ul id="dataviz-tabs" class="nav nav-tabs">
    <li id="nav-tab-dataviz-main" class="active"><a href="#dataviz-main" data-toggle="tab"><?php echo jLocale::get('dataviz~dataviz.dock.title'); ?></a></li>
  </ul>

</div>
<?php 
}
return true;}
