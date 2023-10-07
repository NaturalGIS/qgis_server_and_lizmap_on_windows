<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/project_list_help.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jlocale.php');
function template_meta_f363e6ca460f20cca4bef508ba49b954($t){

}
function template_f363e6ca460f20cca4bef508ba49b954($t){
?><div id="lizmap_project_list_help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?php echo jLocale::get('admin.project.modal.title'); ?></h3>
    </div>
    <div class="modal-body">
        <p>
            <?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.description.html", array($t->_vars['lizmapVersion'], ($t->_vars['serverVersions']['qgis_server_version_human_readable'])));?>

        </p>

        <ul>
            <li><?php echo jLocale::get('admin.project.rules.list.blocking.html'); ?></li>
            <ul class="rules">
                <li class="blocker"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.blocking.target.html", array($t->_vars['minimumLizmapTargetVersionRequired'] - 0.1));?></li>
            </ul>

            <li><?php echo jLocale::get('admin.project.rules.list.warnings.html'); ?></li>
            <ul>
                <li><?php echo jLocale::get('admin.project.list.column.qgis.desktop.version.label'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.qgis.version.warning.html", array($t->_vars['serverVersions']['qgis_server_version_old']));?></li>
                    <li class="error"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.qgis.version.error.html", array( $t->_vars['serverVersions']['qgis_server_version_next']));?></li>
                </ul>
                <li><?php echo jLocale::get('admin.project.list.column.target.lizmap.version.label.longer'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.target.version.html", array($t->_vars['minimumLizmapTargetVersionRequired']));?></li>
                </ul>
                <li><?php echo jLocale::get('admin.project.list.column.layers.count.label.longer'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.important.count.layers.html", array($t->_vars['warningLayerCount']));?></li>
                    <li class="error"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.very.important.count.layers.html", array(($t->_vars['errorLayerCount'])));?></li>
                </ul>
                <li><?php echo jLocale::get('admin.project.list.column.crs.label'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php echo jLocale::get('admin.project.rules.list.custom.projection'); ?></li>
                </ul>

                <?php if($t->_vars['hasInspectionData']):?>

                <li><?php echo jLocale::get('admin.project.list.column.invalid.layers.count.label'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php echo jLocale::get('admin.project.rules.list.invalid.datasource.html'); ?></li>
                </ul>
                <li><?php echo jLocale::get('admin.project.list.column.loading.time.label.alt'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.warning.loading.html", array($t->_vars['warningLoadingTime']));?></li>
                    <li class="error"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.error.loading.html", array($t->_vars['errorLoadingTime']));?>

                    </li>
                </ul>
                <li><?php echo jLocale::get('admin.project.list.column.memory.usage.label.alt'); ?></li>
                <ul class="rules">
                    <li class="warning"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.warning.memory.html", array($t->_vars['warningMemory']));?>

                    </li>
                    <li class="error"><?php jtpl_function_html_jlocale( $t,"admin.project.rules.list.error.memory.html", array($t->_vars['errorMemory']));?></li>
                </ul>
                <?php endif;?>

            </ul>
        </ul>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo jLocale::get('admin.project.modal.button.close'); ?></button>
    </div>
</div>
<?php 
}
return true;}
