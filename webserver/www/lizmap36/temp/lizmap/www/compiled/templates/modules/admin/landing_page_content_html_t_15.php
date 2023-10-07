<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/landing_page_content.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_control.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formsubmit.php');
function template_meta_782a3c3aab9d436c26a60dd71bfaa9ac($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder('html');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_782a3c3aab9d436c26a60dd71bfaa9ac($t){
?>  <?php  if(jAcl2::check('lizmap.admin.home.page.update')):?>
  <!--Services-->
  <div>
    <h2><?php echo jLocale::get('admin.menu.lizmap.landingPageContent.label'); ?></h2>

    <?php  $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['form'],'html',array(), 'admin~landing_page_content:save',array());$t->_privateVars['__formTplController']->startForm();?>

        <?php $ctrls_to_display=null;$ctrls_notto_display=null;
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(true, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>
            <p> <?php jtpl_function_html_ctrl_control( $t);?> </p>
        <?php }?>
        <div> <?php jtpl_function_html_formsubmit( $t);?> </div>
    <?php $t->_privateVars['__formTplController']->endForm();
unset($t->_privateVars['__formTplController']);?>

  </div>
  <?php  endif; ?>
<?php 
}
return true;}
