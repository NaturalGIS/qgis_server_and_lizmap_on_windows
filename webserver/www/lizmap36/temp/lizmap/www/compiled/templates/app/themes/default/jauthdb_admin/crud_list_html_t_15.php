<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/jauthdb_admin/crud_list.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/common/function.cycle.php');
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/html/function.pagelinks_bootstrap.php');
function template_meta_417655fad46f8fc4885c249fa6cf916c($t){
jtpl_meta_html_html( $t,'assets','jauthdb_admin');

}
function template_417655fad46f8fc4885c249fa6cf916c($t){
?>

<h1><?php echo jLocale::get('jauthdb_admin~crud.title.list'); ?></h1>

<?php if($t->_vars['showfilter']):?>

    <form action="<?php jtpl_function_html_formurl( $t,'jauthdb_admin~default:index');?>" method="get">
        <div>
            <!--<label for="user-list-filter"><?php echo jLocale::get('jauthdb_admin~crud.search.form.keyword.label'); ?></label>-->
            <input type="text" name="filter" value="<?php echo htmlspecialchars($t->_vars['filter'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>" id="user-list-filter" />
            <button type="submit" class="btn btn-small"><?php echo jLocale::get('jauthdb_admin~crud.search.button.label'); ?></button>
        </div>
    </form>
<?php endif;?>

<?php if($t->_vars['canview']):?>
<form action="<?php jtpl_function_html_formurl( $t,'jauthdb_admin~default:view');?>" method="get" class="form-inline">
    <div>
        <label for="search-login"><?php echo jLocale::get('jauthdb_admin~crud.title.view'); ?></label>
        <input id="search-login" name="j_user_login" data-link="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:autocomplete');?>">
        <button type="submit" class="btn btn-small"><?php echo jLocale::get('jauthdb_admin~crud.link.view.record'); ?></button>
    </div>
</form>
<?php endif;?>

<table class="records-list table table-hover table-condensed">
<thead>
<tr>
    <th><?php echo jLocale::get('jauthdb_admin~crud.list.col.login'); ?></th>
    <th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php foreach($t->_vars['list'] as $t->_vars['record']):?>

<tr class="<?php jtpl_function_common_cycle( $t,array('odd','even'));?>">
    <td><?php echo htmlspecialchars($t->_vars['record']->login, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></td>
    <td>
        <?php if($t->_vars['canview']):?>
        <a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:view',array('j_user_login'=>$t->_vars['record']->login));?>" class="btn btn-small"><?php echo jLocale::get('jauthdb_admin~crud.link.view.record'); ?></a>
        <?php endif;?>
    </td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php if($t->_vars['recordCount'] > $t->_vars['listPageSize']):?>
<div class="record-pages-list">Pages : <?php jtpl_function_html_pagelinks_bootstrap( $t,'jauthdb_admin~default:index', array(), $t->_vars['recordCount'], $t->_vars['page'], $t->_vars['listPageSize'], 'offset' );?></div>
<?php endif;?>
<?php if($t->_vars['cancreate']):?>
<p><a href="<?php jtpl_function_html_jurl( $t,'jauthdb_admin~default:precreate');?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.create.record'); ?></a></p>
<?php endif;?>

<?php 
}
return true;}
