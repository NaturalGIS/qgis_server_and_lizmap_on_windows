<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_bottomdock.tpl') > 1648478290){ return false;
} else {
function template_meta_819f497f7428e30e5af54e87f7c4d18a($t){

}
function template_819f497f7428e30e5af54e87f7c4d18a($t){
?>    <div class="tabbable tabs-below">
      <div id="bottom-dock-content" class="tab-content">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
        <div class="tab-pane<?php if($t->_vars['dock']->order==1):?> active<?php endif;?>" id="<?php echo $t->_vars['dock']->id; ?>">
          <?php echo $t->_vars['dock']->fetchContent(); ?>
        </div>
      <?php endforeach;?>
      </div>

      <div id="bottom-dock-window-buttons">
        <button class="btn-bottomdock-clear btn btn-mini" type="button" title="<?php echo jLocale::get('view~map.bottomdock.toolbar.btn.clear.title'); ?>"><?php echo jLocale::get('view~map.bottomdock.toolbar.btn.clear.title'); ?></button>
        &nbsp;
        <button class="btn-bottomdock-size btn btn-mini" type="button" title="<?php echo jLocale::get('view~map.bottomdock.toolbar.btn.size.maximize.title'); ?>"><?php echo jLocale::get('view~map.bottomdock.toolbar.btn.size.maximize.title'); ?></button>
      </div>

      <ul id="bottom-dock-tabs" class="nav nav-tabs">
      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>

        <li id="nav-tab-<?php echo $t->_vars['dock']->id; ?>" <?php if($t->_vars['dock']->order==1):?> class="active"<?php endif;?>><a href="#<?php echo $t->_vars['dock']->id; ?>" data-toggle="tab"><?php echo $t->_vars['dock']->title; ?></a></li>
      <?php endforeach;?>

      </ul>
    </div>
<?php 
}
return true;}
