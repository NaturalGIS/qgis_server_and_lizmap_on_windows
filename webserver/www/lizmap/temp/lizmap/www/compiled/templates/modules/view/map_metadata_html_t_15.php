<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_metadata.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/modifier.nl2br.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/common/modifier.replace.php');
function template_meta_3947a66f8d476b4bf0962aeec8782481($t){

}
function template_3947a66f8d476b4bf0962aeec8782481($t){
?>    <div>
      <div>
        <p>
          <img src="<?php jtpl_function_html_jurl( $t,'view~media:illustration', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project']));?>" alt="project image" class="img-polaroid liz-project-img" width="200">


          <dl class="dl-vertical">
            <?php if($t->_vars['WMSServiceTitle']):?>
            <dt><?php echo jLocale::get('view~map.metadata.description.title'); ?></dt>
            <dd><?php echo $t->_vars['WMSServiceTitle']; ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSServiceAbstract']):?>
            <dt><?php echo jLocale::get('view~map.metadata.description.abstract'); ?></dt>
            <dd><?php echo jtpl_modifier_html_nl2br($t->_vars['WMSServiceAbstract']); ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSContactOrganization']):?>
            <dt><?php echo jLocale::get('view~map.metadata.contact.organization'); ?></dt>
            <dd><?php echo $t->_vars['WMSContactOrganization']; ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSContactPerson']):?>
            <dt><?php echo jLocale::get('view~map.metadata.contact.person'); ?></dt>
            <dd><?php echo $t->_vars['WMSContactPerson']; ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSContactMail']):?>
            <dt><?php echo jLocale::get('view~map.metadata.contact.email'); ?></dt>
            <dd><?php echo jtpl_modifier_common_replace($t->_vars['WMSContactMail'],'@',' (at) '); ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSContactPhone']):?>
            <dt><?php echo jLocale::get('view~map.metadata.contact.phone'); ?></dt>
            <dd><?php echo $t->_vars['WMSContactPhone']; ?>&nbsp;</dd>
            <br/>
            <?php endif;?>


            <?php if($t->_vars['WMSOnlineResource']):?>
            <dt><?php echo jLocale::get('view~map.metadata.resources.website'); ?></dt>
            <dd><a href="<?php echo $t->_vars['WMSOnlineResource']; ?>" target="_blank"><?php echo $t->_vars['WMSOnlineResource']; ?></a></dd>
            <br/>
            <?php endif;?>


            <dt><?php echo jLocale::get('view~map.metadata.properties.projection'); ?></dt>
            <dd><small class="proj"><?php echo $t->_vars['ProjectCrs']; ?>&nbsp;</small></dd>
            <br/>
            <dt><?php echo jLocale::get('view~map.metadata.properties.extent'); ?></dt>
            <dd><small class="bbox"><?php echo $t->_vars['WMSExtent']; ?></small></dd>
            <br/>

            <?php if($t->_vars['wmsGetCapabilitiesUrl']):?>

            <dt><?php echo jLocale::get('view~map.metadata.properties.wmsGetCapabilitiesUrl'); ?></dt>
            <dd><small><a href="<?php echo $t->_vars['wmsGetCapabilitiesUrl']; ?>" target="_blank">WMS Url</a></small></dd>
            <dd><small><a id="metadata-wmts-getcapabilities-url" href="<?php echo $t->_vars['wmtsGetCapabilitiesUrl']; ?>" target="_blank">WMTS Url</a></small></dd>
            <br/>
            <?php endif;?>

          </dl>
        </p>
      </div>
    </div>

<?php 
}
return true;}
