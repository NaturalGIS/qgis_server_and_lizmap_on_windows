<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/config_section.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_label.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_control.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formsubmit.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
function template_meta_9d8559b6b353f047f5b68539c914feb2($t){
jtpl_meta_html_html( $t,'js',$t->_vars['j_basepath'].'assets/js/admin/config_section.js');
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_9d8559b6b353f047f5b68539c914feb2($t){
?>
<?php jtpl_function_html_jmessage_bootstrap( $t);?>

<?php if($t->_vars['form']->getData('new') == 1):?>
<h1><?php echo jLocale::get('admin~admin.form.admin_section.h1.create'); ?></h1>
<?php else:?>

<h1><?php echo jLocale::get('admin~admin.form.admin_section.h1.modify'); ?></h1>
<?php endif;?>

<?php  $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['form'], 'htmlbootstrap',array(), 'admin~maps:saveSection', array());$t->_privateVars['__formTplController']->startForm();?>
<div class="control-group">
  <?php jtpl_function_html_ctrl_label( $t,'path');?>
  <div class="controls"><?php jtpl_function_html_ctrl_control( $t,'path');?></div>
</div>
<div class="control-group">
  <?php jtpl_function_html_ctrl_label( $t,'label');?>
  <div class="controls"><?php jtpl_function_html_ctrl_control( $t,'label');?></div>
</div>
<div class="control-group">
  <?php jtpl_function_html_ctrl_label( $t,'repository');?>
  <div class="controls"><?php jtpl_function_html_ctrl_control( $t,'repository');?></div>
</div>

<?php $ctrls_to_display=null;$ctrls_notto_display=null;
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(true, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>
<div class="control-group">
  <?php jtpl_function_html_ctrl_label( $t);?>
  <div class="controls"><?php jtpl_function_html_ctrl_control( $t);?></div>
</div>
<?php }?>
<div class="jforms-submit-buttons form-actions"><?php jtpl_function_html_formsubmit( $t);?></div>
<?php $t->_privateVars['__formTplController']->endForm();
unset($t->_privateVars['__formTplController']);?>

<div>
  <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:index');?>"><?php echo jLocale::get('admin~admin.configuration.button.back.label'); ?></a>
</div>
<?php 
}
return true;}
