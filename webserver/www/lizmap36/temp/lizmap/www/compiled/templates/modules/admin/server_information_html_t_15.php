<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/server_information.tpl') > 1696403627){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.hook.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jlocale.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/common/modifier2.truncate.php');
function template_meta_3eb77883587d6055bac439d262c682f5($t){
jtpl_meta_html_html( $t,'js',$t->_vars['j_basepath'].'assets/js/admin/copy_to_clipboard.js');

}
function template_3eb77883587d6055bac439d262c682f5($t){
?>


<?php  if(jAcl2::check('lizmap.admin.server.information.view')):?>
  <!--Services-->
  <div id="lizmap_server_information">
    <h2><?php echo jLocale::get('admin.menu.server.information.label'); ?></h2>

    <h3><?php echo jLocale::get('admin.server.information.lizmap.label'); ?></h3>
    <h4><?php echo jLocale::get('admin.server.information.lizmap.info'); ?></h4>
    <table class="table table-striped table-bordered table-server-info table-lizmap-web-client">
        <tr>
            <th><?php echo jLocale::get('admin.server.information.lizmap.info.version'); ?></th>
            <td><?php echo $t->_vars['data']['info']['version']; ?></td>
        </tr>
        <tr>
            <th><?php echo jLocale::get('admin.server.information.lizmap.info.date'); ?></th>
            <td><?php echo $t->_vars['data']['info']['date']; ?></td>
        </tr>
        <tr>
            <th><?php echo jLocale::get('admin.server.information.lizmap.url'); ?></th>
            <td>
                <?php echo $t->_vars['baseUrlApplication']; ?>

                <button type="button" class="btn-small copy-to-clipboard" data-text="<?php echo $t->_vars['baseUrlApplication']; ?>">
                    <svg aria-hidden="true" height="16" width="16" data-view-component="true"><path fill-rule="evenodd" d="M0 6.75C0 5.784.784 5 1.75 5h1.5a.75.75 0 0 1 0 1.5h-1.5a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-1.5a.75.75 0 0 1 1.5 0v1.5A1.75 1.75 0 0 1 9.25 16h-7.5A1.75 1.75 0 0 1 0 14.25v-7.5z"/><path fill-rule="evenodd" d="M5 1.75C5 .784 5.784 0 6.75 0h7.5C15.216 0 16 .784 16 1.75v7.5A1.75 1.75 0 0 1 14.25 11h-7.5A1.75 1.75 0 0 1 5 9.25v-7.5zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25h-7.5z"/></svg>
                </button>
            </td>
        </tr>
    </table>
    <?php jtpl_function_html_hook( $t,'LizmapServerVersion', $t->_vars['data']['info']);?>


    <h3><?php echo jLocale::get('admin.server.information.qgis.label'); ?></h3>

    <?php if(array_key_exists('qgis_server', $t->_vars['data']) && array_key_exists('test', $t->_vars['data']['qgis_server'])):?>

      
      
      <?php if($t->_vars['data']['qgis_server']['test'] == 'OK'):?>
          <p><?php echo jLocale::get('admin.server.information.qgis.test.ok'); ?></p>
      <?php else:?>

          <p><b><?php echo jLocale::get('admin.server.information.qgis.test.error'); ?></b></p>
      <?php endif;?>

    <?php endif;?>

<?php if(array_key_exists('error', $t->_vars['data']['qgis_server_info'])):?>


<?php if($t->_vars['data']['qgis_server']['test'] == 'OK'):?>

    <p>
        <b><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information'); ?></b><br/>
        <?php if($t->_vars['data']['qgis_server_info']['error'] == 'NO_ACCESS'):?>

            <i><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.detail.NO_ACCESS'); ?></i><br>
        <?php else:?>

            <p><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.description'); ?></p>
            <ol>
                <li><?php jtpl_function_html_jlocale( $t,"admin.server.information.qgis.error.fetching.information.qgis.version.html", array($t->_vars['minimumQgisVersion']));?></li>
                <li><?php jtpl_function_html_jlocale( $t,"admin.server.information.qgis.error.fetching.information.plugin.version.html", array($t->_vars['minimumLizmapServer']));?></li>
                <li><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.qgis.url.html'); ?></li>
                <li><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.qgis.lizmap.html'); ?></li>
                <li><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.lizmap.logs.html'); ?></li>
                <li><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.environment.variable'); ?></li>
                <li><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.help'); ?></li>
            </ol>
            <br>
            <?php $t->_vars['lizmapDoc']='https://docs.lizmap.com/current/en/install/pre_requirements.html#lizmap-server-plugin';?>

            <?php $t->_vars['qgisDoc']='https://docs.qgis.org/latest/en/docs/server_manual/config.html#environment-variables';?>
            <a href="<?php echo $t->_vars['lizmapDoc']; ?>" target="_blank"><?php echo $t->_vars['lizmapDoc']; ?></a>
            <br>
            <a href="<?php echo $t->_vars['qgisDoc']; ?>" target="_blank"><?php echo $t->_vars['qgisDoc']; ?></a>
            <br>
            <?php if($t->_vars['data']['qgis_server_info']['error_http_code'] == '200'):?>

                
                <?php $t->_vars['errorcode']='Unknown';?>
            <?php else:?>
                <?php $t->_vars['errorcode']=$t->_vars['data']['qgis_server_info']['error_http_code'];?>
            <?php endif;?>
            <i><?php echo jLocale::get('admin.server.information.qgis.error.fetching.information.detail.HTTP_ERROR'); ?> <?php echo $t->_vars['errorcode']; ?></i><br>
        <?php endif;?>

    </p>
<?php endif;?>
<?php else:?>

    <h4><?php echo jLocale::get('admin.server.information.qgis.metadata'); ?></h4>
    <table class="table table-condensed table-striped table-bordered table-server-info table-qgis-server">
        <tr>
            <th><?php echo jLocale::get('admin.server.information.qgis.version'); ?></th>
            <td>
                <a href="https://github.com/qgis/QGIS/releases/tag/<?php echo $t->_vars['data']['qgis_server_info']['metadata']['tag']; ?>" target="_blank">
                    <?php echo $t->_vars['data']['qgis_server_info']['metadata']['version']; ?>

                </a>
            </td>
        </tr>
        <tr>
            <th><?php echo jLocale::get('admin.server.information.qgis.name'); ?></th>
            <td><?php echo $t->_vars['data']['qgis_server_info']['metadata']['name']; ?></td>
        </tr>
        <tr>
            <th><?php echo jLocale::get('admin.server.information.qgis.commit_id'); ?></th>
            <td><a href="https://github.com/qgis/QGIS/commit/<?php echo $t->_vars['data']['qgis_server_info']['metadata']['commit_id']; ?>" target="_blank"><?php echo $t->_vars['data']['qgis_server_info']['metadata']['commit_id']; ?></a></td>
        </tr>
        <tr>
            <th>Py-QGIS-Server</th>
            <td>
                <?php if($t->_vars['data']['qgis_server_info']['py_qgis_server']['found']):?>

                    <?php if($t->_vars['data']['qgis_server_info']['py_qgis_server']['stable']):?>
                    <a href="https://github.com/3liz/py-qgis-server/releases/tag/<?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['version']; ?>" target="_blank">
                        <?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['version']; ?>

                    </a>
                    <?php else:?>
                    <a href="https://github.com/3liz/py-qgis-server/commit/<?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['commit_id']; ?>" target="_blank">
                        <?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['version']; ?> - <?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['commit_id']; ?>

                    </a>
                    <?php endif;?>
                <?php else:?>
                    <?php echo $t->_vars['data']['qgis_server_info']['py_qgis_server']['version']; ?>

                <?php endif;?>
            </td>
        </tr>
        <?php if($t->_vars['qgisServerNeedsUpdate'] ):?>
        <tr>
            <th><?php echo jLocale::get('admin.server.information.qgis.action'); ?></th>
            <td style="background-color:lightcoral;"><strong><?php echo $t->_vars['updateQgisServer']; ?></strong></td>
        </tr>
        <?php endif;?>

    </table>
    <?php jtpl_function_html_hook( $t,'QgisServerVersion', $t->_vars['data']['qgis_server_info']['metadata']);?>

    <h4><?php echo jLocale::get('admin.server.information.qgis.plugins'); ?></h4>
    <table class="table table-condensed table-striped table-bordered table-server-info table-qgis-server-plugins">
        <tr>
            <th style="width:20%;"><?php echo jLocale::get('admin.server.information.qgis.plugin'); ?></th>
            <th style="width:20%;"><?php echo jLocale::get('admin.server.information.qgis.plugin.version'); ?></th>
            <?php if($t->_vars['displayPluginActionColumn'] ):?>

                <th><?php echo jLocale::get('admin.server.information.qgis.plugin.action'); ?></th>
            <?php endif;?>

        </tr>
        <?php foreach($t->_vars['data']['qgis_server_info']['plugins'] as $t->_vars['name']=>$t->_vars['version']):?>
        <tr>
            <?php if($t->_vars['version']['name']):?>
            
            <th style="width:20%;"><?php echo $t->_vars['version']['name']; ?></th>
            <?php else:?>

            <th style="width:20%;"><?php echo $t->_vars['name']; ?></th>
            <?php endif;?>

            <td style="width:20%;">
                <?php if($t->_vars['version']['repository']):?>
                    <?php if($t->_vars['version']['commitNumber'] == 1):?>
                        
                        <a href="<?php echo $t->_vars['version']['repository']; ?>/releases/tag/<?php echo $t->_vars['version']['version']; ?>" target="_blank"><?php echo $t->_vars['version']['version']; ?></a>
                    <?php else:?>

                        <a href="<?php echo $t->_vars['version']['repository']; ?>/commit/<?php echo $t->_vars['version']['commitSha1']; ?>" target="_blank"><?php echo $t->_vars['version']['version']; ?> - <?php echo jtpl_modifier2_common_truncate($t, $t->_vars['version']['commitSha1'],7,''); ?></a>
                    <?php endif;?>

                <?php else:?>
                    <?php echo $t->_vars['version']['version']; ?>

                <?php endif;?>
            </td>
            <?php if($t->_vars['displayPluginActionColumn'] ):?>
                <?php if($t->_vars['name'] == 'lizmap_server' && $t->_vars['lizmapQgisServerNeedsUpdate']):?>
                    <td style="background-color:lightcoral;"><strong><?php echo $t->_vars['lizmapPluginUpdate']; ?></strong></td>
                <?php else:?>

                <td></td>
                <?php endif;?>
            <?php endif;?>
        </tr>
        <?php endforeach;?>
    </table>
    <?php jtpl_function_html_hook( $t,'QgisServerPlugins', $t->_vars['data']['qgis_server_info']['plugins']);?>

<?php endif;?>

  </div>
<?php  endif; ?>
<?php 
}
return true;}
