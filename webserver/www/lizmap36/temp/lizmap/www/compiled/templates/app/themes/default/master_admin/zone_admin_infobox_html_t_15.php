<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/master_admin/zone_admin_infobox.tpl') > 1696349194){ return false;
} else {
function template_meta_6c6559916a79d12f5d54d59e4a342ab1($t){
$t->meta('lizmap~user_menu');

}
function template_6c6559916a79d12f5d54d59e4a342ab1($t){
?><ul class="nav pull-right">
  <?php foreach($t->_vars['infoboxitems'] as $t->_vars['item']):?>
    <li class="<?php echo $t->_vars['item']->id; ?>">
     <?php if($t->_vars['item']->type == 'url'):?>

     <a href="<?php echo htmlspecialchars($t->_vars['item']->content, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>" title="<?php echo htmlspecialchars($t->_vars['item']->label, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>">
       <?php if($t->_vars['item']->icon):?><span class="icon"></span><?php endif;?>

       <span class="text hidden-phone"><?php echo htmlspecialchars($t->_vars['item']->label, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></span>
     </a>
     <?php else:?>

     <p class="navbar-text"><?php echo $t->_vars['item']->content; ?></p>
     <?php endif;?>

    </li>
  <?php endforeach;?>
    <?php $t->display('lizmap~user_menu');?>
</ul>
<?php 
}
return true;}
