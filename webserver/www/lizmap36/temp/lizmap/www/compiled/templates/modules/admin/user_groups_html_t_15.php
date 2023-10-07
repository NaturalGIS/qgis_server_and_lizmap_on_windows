<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\admin\templates/user_groups.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurlparam.php');
function template_meta_12127c81da923d490e295efa396316c0($t){

}
function template_12127c81da923d490e295efa396316c0($t){
?><h3><?php echo jLocale::get('admin~user.acl.form.title'); ?></h3>

<?php if(count($t->_vars['usergroups'])):?>

<table class="records-list table table-hover table-condensed">
    <tr>
        <th>Groupe</th>
        <th>&nbsp;</th>
    </tr>
    <?php foreach($t->_vars['usergroups'] as $t->_vars['group']):?>
        <tr>
            <td>
                <?php echo $t->_vars['group']->name; ?>

            </td>
            <td>
               <a class="crud-link btn" href="<?php jtpl_function_html_jurl( $t,'admin~acl:removegroup', array('user'=>$t->_vars['user'], 'grpid'=>$t->_vars['group']->id_aclgrp));?>"
                  title="<?php echo jLocale::get('jacl2db_admin~acl2.remove.group.tooltip'); ?>"><?php echo jLocale::get('admin~user.acl.form.remove.from.group'); ?></a>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php else:?>
    <p><?php echo jLocale::get('admin~user.acl.no.group'); ?></p>
<?php endif;?>


<?php if(count($t->_vars['groups'])):?>
<form action="<?php jtpl_function_html_formurl( $t,'admin~acl:addgroup', array('user'=>$t->_vars['user']));?>" method="post">
    <?php jtpl_function_html_formurlparam( $t);?>
    <label for="user-add-group"><?php echo jLocale::get('admin~user.acl.form.add.to.group'); ?></label>
    <select name="grpid" id="user-add-group">
        <?php foreach($t->_vars['groups'] as $t->_vars['group']):?>

            <option value="<?php echo $t->_vars['group']->id_aclgrp; ?>"><?php echo $t->_vars['group']->name; ?></option>
        <?php endforeach;?>

    </select>
    <br/><input type="submit" value="<?php echo jLocale::get('admin~user.acl.form.add'); ?>" />
</form>
<?php endif;?>

<?php 
}
return true;}
