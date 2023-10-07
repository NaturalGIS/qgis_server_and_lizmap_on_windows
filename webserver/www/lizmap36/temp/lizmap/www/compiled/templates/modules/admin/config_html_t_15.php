<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/config.tpl') > 1696403627){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_label.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_value.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
function template_meta_3b7dfae80c6f46b2d12d26adc8d692dd($t){

}
function template_3b7dfae80c6f46b2d12d26adc8d692dd($t){
?><?php jtpl_function_html_jmessage_bootstrap( $t);?>

  <h1><?php echo jLocale::get('admin~admin.configuration.h1'); ?></h1>


  <div>
    <h2><?php echo jLocale::get('admin~admin.generic.h2'); ?></h2>
    <dl>
      <dt><?php echo jLocale::get('admin~admin.generic.version.number.label'); ?></dt><dd><?php echo $t->_vars['version']; ?></dd>
    </dl>
  </div>

  <?php  if(jAcl2::check('lizmap.admin.services.view')):?>

  <!--Services-->
  <?php  $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['servicesForm'] ,'htmlbootstrap', array());?>
        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.interface.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID'))){
                $form = null;
                $ctrls_to_display = array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('appName', 'onlyMaps', 'projectSwitcher', 'googleAnalyticsID');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                    </tr>
                <?php }?>
            </table>
        </div>

        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.emails.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array( 'adminSenderEmail', 'adminSenderName', 'allowUserAccountRequests', 'adminContactEmail'))){
                $form = null;
                $ctrls_to_display = array( 'adminSenderEmail', 'adminSenderName', 'allowUserAccountRequests', 'adminContactEmail');
                $ctrls_notto_display = null;
            }
            else {
                $form = array( 'adminSenderEmail', 'adminSenderName', 'allowUserAccountRequests', 'adminContactEmail');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                    </tr>
                <?php }?>
            </table>
        </div>

        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.projects.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array('defaultRepository', 'defaultProject', 'rootRepositories'))){
                $form = null;
                $ctrls_to_display = array('defaultRepository', 'defaultProject', 'rootRepositories');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('defaultRepository', 'defaultProject', 'rootRepositories');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                    </tr>
                <?php }?>
            </table>
        </div>

      <div>
          <h2><?php echo jLocale::get('admin~admin.configuration.services.section.features.label'); ?></h2>
          <table class="table services-table">
              <?php if(is_array(array('uploadedImageMaxWidthHeight'))){
                $form = null;
                $ctrls_to_display = array('uploadedImageMaxWidthHeight');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('uploadedImageMaxWidthHeight');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                  <tr>
                      <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                  </tr>
              <?php }?>
          </table>
      </div>



        <?php if($t->_vars['showSystem']):?>
        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.cache.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration'))){
                $form = null;
                $ctrls_to_display = array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('cacheStorageType', 'cacheRootDirectory', 'cacheRedisHost', 'cacheRedisPort', 'cacheRedisDb', 'cacheRedisKeyPrefix', 'cacheExpiration');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                    </tr>
                <?php }?>
            </table>
        </div>

        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.qgis.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight', 'lizmapPluginAPIURL'))){
                $form = null;
                $ctrls_to_display = array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight', 'lizmapPluginAPIURL');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('qgisServerVersion', 'wmsServerURL', 'wmsPublicUrlList', 'relativeWMSPath', 'wmsMaxWidth', 'wmsMaxHeight', 'lizmapPluginAPIURL');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                    </tr>
                <?php }?>
            </table>
        </div>


        <div>
            <h2><?php echo jLocale::get('admin~admin.configuration.services.section.system.label'); ?></h2>
            <table class="table services-table">
                <?php if(is_array(array('debugMode', 'requestProxyEnabled'))){
                $form = null;
                $ctrls_to_display = array('debugMode', 'requestProxyEnabled');
                $ctrls_notto_display = null;
            }
            else {
                $form = array('debugMode', 'requestProxyEnabled');
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>

                    <tr>
                        <?php  if($t->_privateVars['__formTplController']->isCurrentControl('requestProxyEnabled')):?>
                            <?php  if($t->_privateVars['__formTplController']->isControlValueEqualsTo('0')):?>
                                <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                            <?php else:?>
                                <td colspan="2">
                                    <?php jtpl_function_html_ctrl_value( $t);?>
                                </td>
                            <?php  endif; ?>
                        <?php else:?>
                        <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                        <?php  endif; ?>
                    </tr>
                <?php }?>
            </table>
        </div>
        <?php endif;?>
      
        <table class="table services-table">
            <?php $ctrls_to_display=null;$ctrls_notto_display=null;
if (!isset($t->_privateVars['__formTplController'])) {
    if ($form === null) { throw new \Exception("Error: form is missing to process formcontrols"); }
    $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($form,'html');
}

foreach($t->_privateVars['__formTplController']->controlsLoop(false, $ctrls_to_display, $ctrls_notto_display) as $ctrl) {
?>
                <tr>
                    <th><?php jtpl_function_html_ctrl_label( $t);?></th><td><?php jtpl_function_html_ctrl_value( $t);?></td>
                </tr>
            <?php }?>
        </table>

    <?php $t->_privateVars['__formTplController']->endForm();
unset($t->_privateVars['__formTplController']);?>
    <!-- Modify -->
    <?php  if(jAcl2::check('lizmap.admin.services.update')):?>
    <div class="form-actions">
    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~config:modifyServices');?>">
      <?php echo jLocale::get('admin~admin.configuration.button.modify.service.label'); ?>
    </a>
    </div>
    <?php  endif; ?>
  <?php  endif; ?>
<?php 
}
return true;}
