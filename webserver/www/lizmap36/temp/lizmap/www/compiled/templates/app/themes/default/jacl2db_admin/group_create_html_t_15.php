<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/jacl2db_admin/group_create.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurl.php');
function template_meta_e0d862e48f970157a3d2e75427e10a76($t){
jtpl_meta_html_html( $t,'assets','jacl2_admin');

}
function template_e0d862e48f970157a3d2e75427e10a76($t){
?>

<h1><?php echo jLocale::get('acl2.create.group'); ?></h1>
<form action="<?php jtpl_function_html_formurl( $t,'jacl2db_admin~groups:newgroup');?>" method="post" id="create-group">
<table class="table">
    <tr>
        <td><label for="grp_name"><?php echo jLocale::get('acl2.group.name.label'); ?></label></td>
        <td><input id="grp_name" name="name" type="text" required value="<?php echo $t->_vars['groupname']; ?>"/></td>
    </tr>
    <tr>
        <td><label for="grp_id"><?php echo jLocale::get('acl2.group.id.label'); ?></label></td>
        <td><input id="grp_id" name="id" type="text" required/>
        (<?php echo jLocale::get('acl2.group.id.help'); ?>)
        </td>
    </tr>
    <tr>
        <td><label for="rights-copy"><?php echo jLocale::get('acl2.group.copy.label'); ?></label></td>
        <td><select id="rights-copy" name="rights-copy">
            <option value=""></option>
            <?php foreach($t->_vars['groups'] as $t->_vars['group']):?>

                <option value="<?php echo $t->_vars['group']->id_aclgrp; ?>"><?php echo $t->_vars['group']->name; ?></option>
            <?php endforeach;?>

            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="<?php echo jLocale::get('acl2.create.group'); ?>" class="btn"/></td>
    </tr>
</table>
</form><?php 
}
return true;}
