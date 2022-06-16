<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/config_section.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_9d8559b6b353f047f5b68539c914feb2($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_9d8559b6b353f047f5b68539c914feb2($t){
?><?php jtpl_function_html_jmessage_bootstrap( $t);?>
<?php if($t->_vars['form']->getData('new') == 1):?>
<h1><?php echo jLocale::get('admin~admin.form.admin_section.h1.create'); ?></h1>
<?php else:?>

<h1><?php echo jLocale::get('admin~admin.form.admin_section.h1.modify'); ?></h1>
<?php endif;?>

<?php  $formfull = $t->_vars['form'];
    $formfullBuilder = $formfull->getBuilder( 'htmlbootstrap');
    $formfullBuilder->setOptions(array());
    $formfullBuilder->setAction( 'admin~maps:saveSection', array());
    $formfullBuilder->outputHeader();
    $formfullBuilder->outputAllControls();
    $formfullBuilder->outputFooter();?>

<div>
  <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:index');?>"><?php echo jLocale::get('admin~admin.configuration.button.back.label'); ?></a>
</div>
<?php 
}
return true;}
