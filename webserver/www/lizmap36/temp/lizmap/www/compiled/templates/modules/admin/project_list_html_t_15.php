<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/project_list.tpl') > 1696349194){ return false;
} else {
function template_meta_b754ac26cd2868be94d4485974d1e6d6($t){

}
function template_b754ac26cd2868be94d4485974d1e6d6($t){
?><?php  if(jAcl2::check('lizmap.admin.project.list.view')):?>

<h2><?php echo jLocale::get('admin.menu.lizmap.project.list.label'); ?></h2>

<div id="lizmap_project_list_container">
    <div id="lizmap_project_list">
        <?php echo $t->_vars['projectList']; ?>

    </div>
</div>

<?php  endif; ?>
<?php 
}
return true;}
