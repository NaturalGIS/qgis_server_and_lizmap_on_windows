<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/project_list_zone.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/common/modifier2.truncate.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/common/modifier.number_format.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/common/modifier.jdatetime.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/html/modifier.nl2br.php');
function template_meta_7f0a8071ac1443625c3ce3dca160a7d0($t){
jtpl_meta_html_html( $t,'css',$t->_vars['basePath'].'assets/css/dataTables.bootstrap.min.css');
jtpl_meta_html_html( $t,'css',$t->_vars['basePath'].'assets/css/responsive.dataTables.min.css');
jtpl_meta_html_html( $t,'js',$t->_vars['basePath'].'assets/js/jquery.dataTables.min.js');
jtpl_meta_html_html( $t,'js',$t->_vars['basePath'].'assets/js/dataTables.responsive.min.js');
jtpl_meta_html_html( $t,'js',$t->_vars['basePath'].'assets/js/admin/activate_datatable.js');
$t->meta('admin~project_list_help');

}
function template_7f0a8071ac1443625c3ce3dca160a7d0($t){
?>






<?php $t->_vars['tableClass']='';?>
<?php if($t->_vars['hasInspectionData']):?>
    <?php $t->_vars['tableClass']='has_inspection_data';?>
<?php endif;?>

<?php if($t->_vars['qgisServerOk'] == false):?>


<div>
    <?php echo jLocale::get('admin.server.information.error'); ?>

</div>
<?php endif;?>


<!-- Help button about the colours used in the table -->
<a href="#lizmap_project_list_help" role="button" class="btn pull-right" data-toggle="modal"><?php echo jLocale::get('admin.project.modal.title'); ?></a>
<!-- The modal div code is at the bottom of this file -->

<!-- Sentence displayed when the user clicks on a line of the projects table
to view the hidden columns data and when there is no data for these columns -->
<span id="lizmap_project_list_no_data_label" style="display: none;"><?php echo jLocale::get('admin.project.list.no.hidden.column.content'); ?></span>

<!-- The table contains the projects data. Datatables is used to improve the UX -->
<table class="lizmap_project_list table table-condensed table-bordered <?php echo $t->_vars['tableClass']; ?>" style="width:100%">
    <thead>
        <tr>
            <th></th>
            <th><?php echo jLocale::get('admin.project.list.column.repository.label'); ?></th>
            <th><?php echo jLocale::get('admin.project.list.column.project.label'); ?></th>
            <th><?php echo jLocale::get('admin.project.list.column.layers.count.label'); ?></th>
            <?php if($t->_vars['hasInspectionData']):?>

                <th><?php echo jLocale::get('admin.project.list.column.invalid.layers.count.label'); ?></th>
                <th><?php echo jLocale::get('admin.project.list.column.project.has.log.label'); ?></th>
                <th><?php echo jLocale::get('admin.project.list.column.loading.time.label'); ?></th>
                <th><?php echo jLocale::get('admin.project.list.column.memory.usage.label'); ?></th>
            <?php endif;?>

            <th><?php echo jLocale::get('admin.project.list.column.qgis.desktop.version.label'); ?></th>
            <th><?php echo jLocale::get('admin.project.list.column.target.lizmap.version.label'); ?></th>
            <!-- <th class='lizmap_plugin_version'><?php echo jLocale::get('admin.project.list.column.lizmap.plugin.version.label'); ?></th> -->
            <th><?php echo jLocale::get('admin.project.list.column.hidden.project.label'); ?></th>
            <th><?php echo jLocale::get('admin.project.list.column.authorized.groups.label'); ?></th>
            <th><?php echo jLocale::get('admin.project.list.column.project.file.time.label'); ?></th>
            <?php if($t->_vars['hasInspectionData']):?>

                <th><?php echo jLocale::get('admin.project.list.column.inspection.file.time.label'); ?></th>
            <?php endif;?>

            <th><?php echo jLocale::get('admin.project.list.column.crs.label'); ?></th>
            <?php if($t->_vars['hasInspectionData']):?>

                <th><?php echo jLocale::get('admin.project.list.column.invalid.layers.list.label'); ?></th>
                <th><?php echo jLocale::get('admin.project.list.column.project.qgis.log.label'); ?></th>
            <?php endif;?>


        </tr>
    </thead>

    <tbody>

    <!-- colors for warnings and errors -->
    <?php $t->_vars['warningLayerCount'] = 100;?>
    <?php $t->_vars['errorLayerCount'] = 200;?>
    <?php $t->_vars['warningLoadingTime'] = 30.0;?>
    <?php $t->_vars['errorLoadingTime'] = 60.0;?>
    <?php $t->_vars['warningMemory'] = 100;?>
    <?php $t->_vars['errorMemory'] = 250;?>

    <?php foreach($t->_vars['mapItems'] as $t->_vars['mi']):?>
    <?php if($t->_vars['mi']->type == 'rep'):?>
        <?php foreach($t->_vars['mi']->childItems as $t->_vars['p']):?>
        <tr>
            <!-- Empty first column to use with the responsive (contains the triangle to open line details) -->
            <td title="<?php echo jLocale::get('admin.project.list.column.show.line.hidden.columns'); ?>">
            </td>

            <!-- repository -->
            <td title="<?php if(!empty($t->_vars['mi']->title)):?><?php echo htmlspecialchars(strip_tags($t->_vars['mi']->title), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?><?php endif;?>">
                <?php echo $t->_vars['mi']->id; ?>

            </td>

            <!-- project - KEEP the line break after the title to improve the tooltip readability-->
            <td title="<?php if(!empty($t->_vars['p']['title'])):?><?php echo htmlspecialchars(strip_tags($t->_vars['p']['title']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?><?php endif;?>
<?php if(!empty($t->_vars['p']['abstract'])):?><?php echo jtpl_modifier2_common_truncate($t, htmlspecialchars(strip_tags($t->_vars['p']['abstract']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"),150); ?><?php endif;?>">
                <a target="_blank" href="<?php echo $t->_vars['p']['url']; ?>"><?php echo $t->_vars['p']['id']; ?></a>
            </td>

            <!-- Layer count -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['p']['layer_count'] > $t->_vars['warningLayerCount']):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.layers.count.warning.label');?>
            <?php endif;?>
            <?php if($t->_vars['p']['layer_count'] > $t->_vars['errorLayerCount']):?>
                <?php $t->_vars['class'] = 'liz-error';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.layers.count.error.label');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php echo $t->_vars['p']['layer_count']; ?>

            </td>

        <?php if($t->_vars['hasInspectionData']):?>

            <!-- Invalid layers count -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['p']['invalid_layers_count'] > 0):?>
                <?php $t->_vars['class'] = 'liz-error';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.invalid.layers.count.error.label');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php echo $t->_vars['p']['invalid_layers_count']; ?>

            </td>

            <!-- A QGIS log exists -->
            <td><?php if(!empty($t->_vars['p']['qgis_log']) && !empty(trim($t->_vars['p']['qgis_log']))):?>🔴<?php endif;?></td>

            <!-- loading time -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['p']['loading_time'] > $t->_vars['warningLoadingTime']):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.loading.time.warning.label');?>
            <?php endif;?>
            <?php if($t->_vars['p']['loading_time'] > $t->_vars['errorLoadingTime']):?>
                <?php $t->_vars['class'] = 'liz-error';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.loading.time.error.label');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php if(!empty($t->_vars['p']['loading_time'])):?>

                <?php echo jtpl_modifier_common_number_format($t->_vars['p']['loading_time'],2,'.',' '); ?>

                <?php endif;?>
            </td>

            <!-- Memory usage -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['p']['memory_usage'] > $t->_vars['warningMemory'] ):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.memory.usage.warning.label');?>
            <?php endif;?>
            <?php if($t->_vars['p']['memory_usage'] > $t->_vars['errorMemory'] ):?>
                <?php $t->_vars['class'] = 'liz-error';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.memory.usage.error.label');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php if(!empty($t->_vars['p']['memory_usage'])):?>

                <?php echo jtpl_modifier_common_number_format($t->_vars['p']['memory_usage'],2,'.',' '); ?>

                <?php endif;?>
            </td>

        <?php endif;?>

            <!-- QGIS project version -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['serverVersions']['qgis_server_version_int'] && $t->_vars['serverVersions']['qgis_server_version_int'] - $t->_vars['p']['qgis_version_int'] > $t->_vars['oldQgisVersionDiff'] ):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.qgis.desktop.version.too.old').' ('.jLocale::get('admin.form.admin_services.qgisServerVersion.label').': '.$t->_vars['serverVersions']['qgis_server_version'].')';?>
            <?php endif;?>
            <?php if($t->_vars['serverVersions']['qgis_server_version_int'] && $t->_vars['p']['qgis_version_int'] > $t->_vars['serverVersions']['qgis_server_version_int']):?>
                <?php $t->_vars['class'] = 'liz-error';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.qgis.desktop.version.above.server') .' ('.$t->_vars['serverVersions']['qgis_server_version'].')';?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php echo $t->_vars['p']['qgis_version']; ?>

            </td>

            <!-- Target version of Lizmap Web Client -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if($t->_vars['p']['needs_update_error']):?>
                <?php $t->_vars['class'] = 'liz-blocker';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.update.in.qgis.desktop');?>
            <?php endif;?>
            <?php if($t->_vars['p']['needs_update_warning']):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.update.soon.in.qgis.desktop');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php echo $t->_vars['p']['lizmap_web_client_target_version_display']; ?>

            </td>


            <!-- Version of Lizmap plugin for QGIS Desktop -->
            <!-- <td>
                <?php echo $t->_vars['p']['lizmap_plugin_version']; ?>

            </td> -->


            <!-- Project hidden -->
            <td>
                <?php if($t->_vars['p']['hidden_project']):?>
                    <?php echo jLocale::get('admin.project.list.column.hidden.project.yes.label'); ?>

                <?php else:?>
                    <?php echo jLocale::get('admin.project.list.column.hidden.project.no.label'); ?>

                <?php endif;?>
            </td>

            <!-- Authorized groups -->
            <td>
                <?php if(!empty($t->_vars['p']['acl_groups'])):?>
                <?php echo htmlspecialchars(strip_tags($t->_vars['p']['acl_groups']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>

                <?php endif;?>
            </td>

            <!-- File time -->
            <td>
                <?php echo jtpl_modifier_common_jdatetime($t->_vars['p']['file_time'],'timestamp','Y-m-d H:i:s'); ?>

            </td>

            <?php if($t->_vars['hasInspectionData']):?>
                <!-- Inspection file time -->
                <td>
                    <?php if(!empty($t->_vars['p']['inspection_timestamp'])):?>
                    <?php echo jtpl_modifier_common_jdatetime($t->_vars['p']['inspection_timestamp'],'timestamp','Y-m-d H:i:s'); ?>

                    <?php endif;?>
                </td>
            <?php endif;?>

            <!-- Projection -->
            <?php $t->_vars['class'] = '';?>
            <?php $t->_vars['title'] = '';?>
            <?php if(substr($t->_vars['p']['projection'], 0, 4) == 'USER'):?>
                <?php $t->_vars['class'] = 'liz-warning';?>
                <?php $t->_vars['title'] = jLocale::get('admin.project.list.column.crs.user.warning.label');?>
            <?php endif;?>
            <td title="<?php echo $t->_vars['title']; ?>" class="<?php echo $t->_vars['class']; ?>">
                <?php if(!empty($t->_vars['p']['projection'])):?>

                <?php echo htmlspecialchars(strip_tags($t->_vars['p']['projection']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>

                <?php endif;?>
            </td>

        <?php if($t->_vars['hasInspectionData']):?>
            <!-- List of invalid layers -->
            <?php if($t->_vars['p']['invalid_layers_count'] > 0):?>
            <td>
                <ul>
                <?php foreach($t->_vars['p']['invalid_layers'] as $t->_vars['id']=>$t->_vars['properties']):?>
                    <li style="cursor: help;" title="<?php echo htmlspecialchars(strip_tags($t->_vars['properties']['source']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>">
                        <?php echo $t->_vars['properties']['name']; ?>

                    </li>
                <?php endforeach;?>
                </ul>
            </td>
            <?php else:?>
            <td></td>
            <?php endif;?>

            <!-- QGIS logs -->
            <td class="lizmap-project-qgis-log">
                <?php if(!empty($t->_vars['p']['qgis_log'])):?>
                <pre>
                <?php echo jtpl_modifier_html_nl2br(htmlspecialchars(strip_tags($t->_vars['p']['qgis_log']), ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8")); ?>

                </pre>
                <?php endif;?>
            </td>
        <?php endif;?>

        </tr>
        <?php endforeach;?>
    <?php endif;?>
    <?php endforeach;?>
    </tbody>
</table>


<!-- Help guide -->
<?php $t->display('admin~project_list_help');?>
<?php 
}
return true;}
