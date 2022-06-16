<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_minidock.tpl') > 1616160726){ return false;
} else {
function template_meta_558795f9224a5817b19cd45e143ef1db($t){

}
function template_558795f9224a5817b19cd45e143ef1db($t){
?>    <div class="tabbable tabs-below">
      <div id="mini-dock-content" class="tab-content">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
        <div class="tab-pane<?php if($t->_vars['dock']->order==1):?> active<?php endif;?>" id="<?php echo $t->_vars['dock']->id; ?>">
          <?php echo $t->_vars['dock']->fetchContent(); ?>
        </div>
      <?php endforeach;?>
      </div>
      <ul id="mini-dock-tabs" class="nav nav-tabs">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
        <li id="nav-tab-<?php echo $t->_vars['dock']->id; ?>" <?php if($t->_vars['dock']->order==1):?> class="active"<?php endif;?>><a href="#<?php echo $t->_vars['dock']->id; ?>" data-toggle="tab"><?php echo $t->_vars['dock']->title; ?></a></li>
      <?php endforeach;?>

      </ul>
    </div>
<?php 
}
return true;}
