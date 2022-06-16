<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/var/themes/default/master_admin/zone_admin_menu.tpl') > 1616160726){ return false;
} else {
function template_meta_e922ee2fb168a056e5d547e492295053($t){

}
function template_e922ee2fb168a056e5d547e492295053($t){
?><?php foreach($t->_vars['menuitems'] as $t->_vars['bloc']):?>
    <?php if(count($t->_vars['bloc']->childItems)):?>
         <?php if($t->_vars['bloc']->label):?><li class="nav-header"><?php echo htmlspecialchars($t->_vars['bloc']->label); ?></li><?php endif;?>
         <?php foreach($t->_vars['bloc']->childItems as $t->_vars['item']):?>
                <li <?php if($t->_vars['item']->id == $t->_vars['selectedMenuItem']):?> class="active"<?php endif;?>>
                    <?php if($t->_vars['item']->type == 'url'):?>
                        <a href="<?php echo htmlspecialchars($t->_vars['item']->content); ?>"<?php if($t->_vars['item']->icon):?>

                        style="background-image:url(<?php echo $t->_vars['item']->icon; ?>);"<?php endif;?>><?php echo htmlspecialchars($t->_vars['item']->label); ?></a>
                    <?php else:?>

                        <?php echo $t->_vars['item']->content; ?>

                    <?php endif;?>
                </li>
         <?php endforeach;?>
    <?php endif;?>
<?php endforeach;?>
<?php 
}
return true;}
