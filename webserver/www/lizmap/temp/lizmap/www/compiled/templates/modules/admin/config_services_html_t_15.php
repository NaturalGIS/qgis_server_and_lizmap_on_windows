<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/config_services.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.ctrl_label.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.ctrl_control.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.formsubmit.php');
function template_meta_4de74a21dd3cb62df6907b40795d93a7($t){
jtpl_meta_html_html( $t,'js',$t->_vars['j_basepath'].'assets/js/services_configuration.js');
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_4de74a21dd3cb62df6907b40795d93a7($t){
?>

<?php jtpl_function_html_jmessage_bootstrap( $t);?>
<h1><?php echo jLocale::get('admin~admin.form.admin_services.h1'); ?></h1>
<?php  $t->_privateVars['__form'] = $t->_vars['form'];
$t->_privateVars['__formbuilder'] = $t->_privateVars['__form']->getBuilder( 'htmlbootstrap');
$t->_privateVars['__formbuilder']->setOptions(array());
$t->_privateVars['__formbuilder']->setAction( 'admin~config:saveServices', array());
$t->_privateVars['__formbuilder']->outputHeader();
$t->_privateVars['__formViewMode'] = 0;
$t->_privateVars['__displayed_ctrl'] = array();
?>

    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.interface.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID'))){
                $ctrls_to_display = array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>

    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.emails.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('allowUserAccountRequests', 'adminContactEmail', 'adminSenderEmail', 'adminSenderName'))){
                $ctrls_to_display = array('allowUserAccountRequests', 'adminContactEmail', 'adminSenderEmail', 'adminSenderName');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('allowUserAccountRequests', 'adminContactEmail', 'adminSenderEmail', 'adminSenderName');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>

    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.projects.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('defaultRepository', 'defaultProject', 'rootRepositories'))){
                $ctrls_to_display = array('defaultRepository', 'defaultProject', 'rootRepositories');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('defaultRepository', 'defaultProject', 'rootRepositories');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>
<?php if($t->_vars['showSystem']):?>
    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.cache.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration'))){
                $ctrls_to_display = array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>

    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.qgis.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight'))){
                $ctrls_to_display = array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>


    <div>
        <h2><?php echo jLocale::get('admin~admin.configuration.services.section.system.label'); ?></h2>
        <table class="table services-table">
            <?php if(is_array(array('debugMode', 'requestProxyEnabled'))){
                $ctrls_to_display = array('debugMode', 'requestProxyEnabled');
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = array('debugMode', 'requestProxyEnabled');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
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

                <tr>
                    <?php  if(isset($t->_privateVars['__ctrlref'])&&($t->_privateVars['__ctrlref']=='requestProxyEnabled')):?>
                        <td colspan="2"><?php jtpl_function_html_ctrl_control( $t);?></td>
                    <?php else:?>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
                    <?php  endif; ?>
                </tr>
            <?php }} $t->_privateVars['__ctrlref']='';?>
        </table>
    </div>
<?php endif;?>
    
    <table class="table services-table">
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
            <tr>
                <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_control( $t);?></td>
            </tr>
        <?php }} $t->_privateVars['__ctrlref']='';?>
    </table>


<div>
    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~config:index');?>"><?php echo jLocale::get('admin~admin.configuration.button.back.label'); ?></a>
    <?php jtpl_function_html_formsubmit( $t);?>
</div>

<?php $t->_privateVars['__formbuilder']->outputFooter();
unset($t->_privateVars['__form']);
unset($t->_privateVars['__formbuilder']);
unset($t->_privateVars['__formViewMode']);
unset($t->_privateVars['__displayed_ctrl']);?>
<?php 
}
return true;}
