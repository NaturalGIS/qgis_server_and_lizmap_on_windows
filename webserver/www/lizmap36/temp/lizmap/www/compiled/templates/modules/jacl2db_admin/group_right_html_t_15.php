<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix-admin-modules\jacl2db_admin\templates/group_right.tpl') > 1696258090){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.formurlparam.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\castor\lib\plugins/common/function.cycle.php');
function template_meta_f6c48d9419d08ca9956e1d2ad6d7ccf3($t){
jtpl_meta_html_html( $t,'assets','jacl2_admin');

}
function template_f6c48d9419d08ca9956e1d2ad6d7ccf3($t){
?>

<h1><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.label'); ?> <?php echo $t->_vars['group']->name; ?></h1>

<?php if($t->_vars['group']->id_aclgrp == '__anonymous'):?>

    <p><?php echo jLocale::get('jacl2db_admin~acl2.anonymous.group.help'); ?></p>

<?php endif;?>



<form id="rights-edit" action="<?php jtpl_function_html_formurl( $t,'jacl2db_admin~groups:saverights', array('group'=>$t->_vars['group']->id_aclgrp));?>" method="post">
<div><?php jtpl_function_html_formurlparam( $t,'jacl2db_admin~groups:saverights', array('group'=>$t->_vars['group']->id_aclgrp));?></div>
<table class="records-list jacl2-list" id="rights-list">
<thead>
    <tr>
        <th ><?php echo jLocale::get('jacl2db_admin~acl2.table.th.rights'); ?></th>
        <th><?php echo $t->_vars['group']->name; ?></th>
    </tr>
</thead>
    <tfoot>
    <tr>
        <td><?php echo jLocale::get('jacl2db_admin~acl2.group.rightres.title'); ?></td>
        <th><a href="<?php jtpl_function_html_jurl( $t,'jacl2db_admin~groups:rightres',array('group'=>$t->_vars['group']->id_aclgrp));?>"><?php echo jLocale::get('jacl2db_admin~acl2.special.rights'); ?></a></th>
    </tr>
    </tfoot>
<tbody>
<?php $t->_vars['currentsbjgroup'] = '---';?>
<?php foreach($t->_vars['rights'] as $t->_vars['subject']=>$t->_vars['right']):?>
<?php if($t->_vars['rightsProperties'][$t->_vars['subject']]['grp'] && $t->_vars['currentsbjgroup'] != $t->_vars['rightsProperties'][$t->_vars['subject']]['grp']):?>
<tr class="<?php jtpl_function_common_cycle( $t,array('odd','even'));?>">
    <th colspan="2"><h3><?php echo $t->_vars['rightsGroupsLabels'][$t->_vars['rightsProperties'][$t->_vars['subject']]['grp']]; ?></h3></th>
</tr><?php $t->_vars['currentsbjgroup'] = $t->_vars['rightsProperties'][$t->_vars['subject']]['grp'];?>
<?php endif;?>
<tr class="<?php jtpl_function_common_cycle( $t,array('odd','even'));?>">
    <th title="<?php echo $t->_vars['subject']; ?>"><?php echo htmlspecialchars($t->_vars['rightsProperties'][$t->_vars['subject']]['label'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?></th>
    <td>
        <?php if($t->_vars['group']->id_aclgrp == '__anonymous' && (substr($t->_vars['subject'], 0 , 4) == 'acl.' || in_array($t->_vars['subject'], array('auth.user.change.password', 'auth.users.create', 'auth.users.delete', 'auth.users.modify', 'auth.user.modify', 'auth.users.change.password'))) ):?>
            <span class="right-no"><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.no'); ?></span>
        <?php else:?>

        <select name="rights[<?php echo $t->_vars['subject']; ?>]" id="<?php echo htmlspecialchars($t->_vars['subject'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>" class="right">
            <option value=""  <?php if($t->_vars['rights'][$t->_vars['subject']] == ''):?>selected="selected"<?php endif;?>><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.no'); ?></option>
            <option value="y" <?php if($t->_vars['rights'][$t->_vars['subject']] == 'y'):?>selected="selected"<?php endif;?>><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.yes'); ?></option>
            <option value="n" <?php if($t->_vars['rights'][$t->_vars['subject']] == 'n'):?>selected="selected"<?php endif;?>><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.forbidden'); ?></option>
        </select>
        <?php endif;?>

    </td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<div class="legend">
    <ul>
        <li><img src="<?php echo $t->_vars['j_jelixwww']; ?>/design/icons/accept.png" alt="yes" /> <span class="right-yes"><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.yes'); ?></span> : <?php echo jLocale::get('jacl2db_admin~acl2.group.help.rights.yes'); ?></li>
        <li><span class="right-no"><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.no'); ?></span>: <?php echo jLocale::get('jacl2db_admin~acl2.group.help.rights.no'); ?></li>
        <li><img src="<?php echo $t->_vars['j_jelixwww']; ?>/design/icons/cancel.png" alt="no" /> <span class="right-forbidden"><?php echo jLocale::get('jacl2db_admin~acl2.group.rights.forbidden'); ?></span>: <?php echo jLocale::get('jacl2db_admin~acl2.group.help.rights.forbidden'); ?></li>
    </ul>
</div>
<input name="group" value="<?php echo $t->_vars['groupId']; ?>" type="hidden"/>
<div><input type="submit" value="<?php echo jLocale::get('jelix~ui.buttons.save'); ?>" />
    <br/>
    <a href="<?php jtpl_function_html_jurl( $t,'jacl2db_admin~groups:allrights');?>"><?php echo jLocale::get('jacl2db_admin~acl2.groups.back.to.rights.list'); ?></a>
    <br/>
    <a href="<?php jtpl_function_html_jurl( $t,'jacl2db_admin~groups:index');?>"><?php echo jLocale::get('jacl2db_admin~acl2.groups.back.to.list'); ?></a>

</div>
</form>

<?php 
}
return true;}
