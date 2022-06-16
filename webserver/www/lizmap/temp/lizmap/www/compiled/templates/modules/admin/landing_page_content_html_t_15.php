<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/landing_page_content.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.ctrl_control.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.formsubmit.php');
function template_meta_782a3c3aab9d436c26a60dd71bfaa9ac($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder('html');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_782a3c3aab9d436c26a60dd71bfaa9ac($t){
?>  <?php  if(jAcl2::check('lizmap.admin.access')):?>
  <!--Services-->
  <div>
    <h2><?php echo jLocale::get('admin.menu.lizmap.landingPageContent.label'); ?></h2>

    <?php  $t->_privateVars['__form'] = $t->_vars['form'];
$t->_privateVars['__formbuilder'] = $t->_privateVars['__form']->getBuilder('html');
$t->_privateVars['__formbuilder']->setOptions(array());
$t->_privateVars['__formbuilder']->setAction( 'admin~landing_page_content:save',array());
$t->_privateVars['__formbuilder']->outputHeader();
$t->_privateVars['__formViewMode'] = 0;
$t->_privateVars['__displayed_ctrl'] = array();
?>

        <?php $ctrls_to_display=null;$ctrls_notto_display=null;
if (!isset($t->_privateVars['__formbuilder'])) {
    $t->_privateVars['__formViewMode'] = 1;
    $t->_privateVars['__formbuilder'] = $t->_privateVars['__form']->getBuilder('html');
}
if (!isset($t->_privateVars['__displayed_ctrl'])) {
    $t->_privateVars['__displayed_ctrl'] = array();
}
$t->_privateVars['__ctrlref']='';

foreach($t->_privateVars['__form']->getRootControls() as $ctrlref=>$ctrl){
    if(!$t->_privateVars['__form']->isActivated($ctrlref)) continue;
    if($ctrl->type == 'reset' || $ctrl->type == 'hidden') continue;
if($ctrl->type == 'submit') continue;if(!isset($t->_privateVars['__displayed_ctrl'][$ctrlref])
       && (  ($ctrls_to_display===null && $ctrls_notto_display === null)
          || ($ctrls_to_display===null && !in_array($ctrlref, $ctrls_notto_display))
          || (is_array($ctrls_to_display) && in_array($ctrlref, $ctrls_to_display) ))) {
        $t->_privateVars['__ctrlref'] = $ctrlref;
        $t->_privateVars['__ctrl'] = $ctrl;
?>
            <p> <?php jtpl_function_html_ctrl_control( $t);?> </p>
        <?php }} $t->_privateVars['__ctrlref']='';?>
        <div> <?php jtpl_function_html_formsubmit( $t);?> </div>
    <?php $t->_privateVars['__formbuilder']->outputFooter();
unset($t->_privateVars['__form']);
unset($t->_privateVars['__formbuilder']);
unset($t->_privateVars['__formViewMode']);
unset($t->_privateVars['__displayed_ctrl']);?>

  </div>
  <?php  endif; ?>

<?php 
}
return true;}
