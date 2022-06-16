<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_rightdock.tpl') > 1648478290){ return false;
} else {
function template_meta_8702eca30685a3a8654b9a2429cc3583($t){

}
function template_8702eca30685a3a8654b9a2429cc3583($t){
?>    <div class="tabbable">
      <ul id="right-dock-tabs" class="nav nav-tabs">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
        <li id="nav-tab-<?php echo $t->_vars['dock']->id; ?>"><a href="#<?php echo $t->_vars['dock']->id; ?>" data-toggle="tab"><?php echo $t->_vars['dock']->title; ?></a></li>
      <?php endforeach;?>

      </ul>
      <div id="right-dock-content" class="tab-content">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
        <div class="tab-pane" id="<?php echo $t->_vars['dock']->id; ?>">
          <?php echo $t->_vars['dock']->fetchContent(); ?>

        </div>
      <?php endforeach;?>
      </div>
    </div>

<button id="right-dock-close" class="btn"></button>
<?php 
}
return true;}
