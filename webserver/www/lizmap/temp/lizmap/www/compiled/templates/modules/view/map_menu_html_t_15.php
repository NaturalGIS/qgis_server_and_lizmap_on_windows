<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_menu.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_e51b39d6833dee459d52341eef7741a9($t){

}
function template_e51b39d6833dee459d52341eef7741a9($t){
?><div>
    <ul class="nav nav-list">
      <?php if($t->_vars['display_home']):?>
      <li class="home">
        <a href="<?php jtpl_function_html_jurl( $t,'view~default:index');?>" rel="tooltip" data-original-title="<?php echo jLocale::get('view~default.home.title'); ?>" data-placement="right" data-container="#content">
          <span class="icon"></span><span class="menu-title"><?php echo jLocale::get('view~default.home.title'); ?></span>
        </a>
      </li>
      <?php endif;?>

      <?php foreach($t->_vars['dockable'] as $t->_vars['dock']):?>
      <li class="<?php echo $t->_vars['dock']->id; ?> nav-dock <?php echo $t->_vars['dock']->menuIconClasses; ?>">
        <a id="button-<?php echo $t->_vars['dock']->id; ?>" rel="tooltip" data-original-title="<?php echo $t->_vars['dock']->title; ?>" data-placement="right" href="#<?php echo $t->_vars['dock']->id; ?>" data-container="#content">
          <?php echo $t->_vars['dock']->icon; ?><span class="menu-title"><?php echo $t->_vars['dock']->title; ?></span>
        </a>
      </li>
      <?php endforeach;?>


      <?php foreach($t->_vars['minidockable'] as $t->_vars['dock']):?>
      <li class="<?php echo $t->_vars['dock']->id; ?> nav-minidock <?php echo $t->_vars['dock']->menuIconClasses; ?>">
        <a id="button-<?php echo $t->_vars['dock']->id; ?>" rel="tooltip" data-original-title="<?php echo $t->_vars['dock']->title; ?>" data-placement="right" href="#<?php echo $t->_vars['dock']->id; ?>" data-container="#content">
          <?php echo $t->_vars['dock']->icon; ?><span class="menu-title"><?php echo $t->_vars['dock']->title; ?></span>
        </a>
      </li>
      <?php endforeach;?>


      <?php foreach($t->_vars['bottomdockable'] as $t->_vars['dock']):?>
      <li class="<?php echo $t->_vars['dock']->id; ?> nav-bottomdock <?php echo $t->_vars['dock']->menuIconClasses; ?>">
        <a id="button-<?php echo $t->_vars['dock']->id; ?>" rel="tooltip" data-original-title="<?php echo $t->_vars['dock']->title; ?>" data-placement="right" href="#<?php echo $t->_vars['dock']->id; ?>" data-container="#content">
          <?php echo $t->_vars['dock']->icon; ?><span class="menu-title"><?php echo $t->_vars['dock']->title; ?></span>
        </a>
      </li>
      <?php endforeach;?>


      <?php foreach($t->_vars['rightdockable'] as $t->_vars['dock']):?>
      <li class="<?php echo $t->_vars['dock']->id; ?> nav-right-dock <?php echo $t->_vars['dock']->menuIconClasses; ?>">
        <a id="button-<?php echo $t->_vars['dock']->id; ?>" rel="tooltip" data-original-title="<?php echo $t->_vars['dock']->title; ?>" data-placement="right" href="#<?php echo $t->_vars['dock']->id; ?>" data-container="#content">
          <?php echo $t->_vars['dock']->icon; ?><span class="menu-title"><?php echo $t->_vars['dock']->title; ?></span>
        </a>
      </li>
      <?php endforeach;?>


    </ul>
</div>
<?php 
}
return true;}
