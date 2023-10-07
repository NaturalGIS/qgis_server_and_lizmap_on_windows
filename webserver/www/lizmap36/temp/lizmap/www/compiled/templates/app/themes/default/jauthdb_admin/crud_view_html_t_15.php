<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/jauthdb_admin/crud_view.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/html/function.formdatafull_bootstrap.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
function template_meta_15028bd3924b5e9fe4987eb05548fe98($t){

}
function template_15028bd3924b5e9fe4987eb05548fe98($t){
?><h1><?php echo jLocale::get('jauthdb_admin~crud.title.view'); ?> <?php echo $t->_vars['id']; ?></h1>
<?php if(count($t->_vars['otherInfo'])):?>

<h2><?php echo jLocale::get('jauthdb_admin~crud.view.primaryinfo'); ?></h2>
<?php endif;?>


<?php jtpl_function_html_formdatafull_bootstrap( $t,$t->_vars['form'], 'htmlbootstrap', $t->_vars['formOptions']);?>

<ul class="crud-links-list inline">
    <?php if($t->_vars['canUpdate']):?><li><a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:preupdate', array('j_user_login'=>$t->_vars['id']));?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.edit.record'); ?></a></li><?php endif;?>
    <?php if($t->_vars['canChangePass']):?><li><a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~password:index', array('j_user_login'=>$t->_vars['id']));?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.change.password'); ?></a></li><?php endif;?>
    <?php if($t->_vars['canDelete']):?><li><a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:confirmdelete', array('j_user_login'=>$t->_vars['id']));?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.delete.record'); ?></a></li><?php endif;?>
    <?php foreach($t->_vars['otherLinks'] as $t->_vars['link']):?>
        <li><a href="<?php echo $t->_vars['link']['url']; ?>" class="crud-link btn"><?php echo $t->_vars['link']['label']; ?></a></li>
    <?php endforeach;?>

</ul>

<?php if(count($t->_vars['otherInfo'])):?>
<h2><?php echo jLocale::get('jauthdb_admin~crud.view.otherinfo'); ?></h2>

<?php foreach($t->_vars['otherInfo'] as $t->_vars['info']):?>

 <?php echo $t->_vars['info']; ?>

<?php endforeach;?>

<?php endif;?>

<hr />
<p><a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:index');?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.return.to.list'); ?></a></p>


<?php 
}
return true;}
