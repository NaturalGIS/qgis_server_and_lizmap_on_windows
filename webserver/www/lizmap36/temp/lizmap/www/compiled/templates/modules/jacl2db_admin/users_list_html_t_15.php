<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix-admin-modules\jacl2db_admin\templates/users_list.tpl') > 1696258090){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
function template_meta_b576fe98cb16c30e23abad803244d47f($t){
jtpl_meta_html_html( $t,'assets','jacl2_admin');
jtpl_meta_html_html( $t,'assets','datatables');

}
function template_b576fe98cb16c30e23abad803244d47f($t){
?>


<h1><?php echo jLocale::get('jacl2db_admin~acl2.rights.management.title'); ?></h1>



<div id="rights-tabs" class="ui-tabs ui-corner-all ui-widget ui-widget-content">
    <ul role="tablist" class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
        <li role="tab" tabindex="0" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab"
            aria-labelledby="ui-id-1"
            aria-selected="false" aria-expanded="false">
            <a href="<?php jtpl_function_html_jurl( $t,'jacl2db_admin~groups:index');?>"  role="presentation"
               tabindex="-1" class="ui-tabs-anchor" id="ui-id-1">
                <span><?php echo jLocale::get('jacl2db_admin~acl2.groups.tab'); ?></span></a></li>
        <li role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active"
            aria-controls="users-panel" aria-labelledby="ui-id-2"
            aria-selected="true" aria-expanded="true"
            >
            <a href="#users-panel"  role="presentation" tabindex="-1"
               class="ui-tabs-anchor" id="ui-id-2">
                <span><?php echo jLocale::get('jacl2db_admin~acl2.users.tab'); ?></span></a></li>
    </ul>
    <div id="users-panel"  aria-labelledby="ui-id-2" role="tabpanel"
         class="ui-tabs-panel ui-corner-bottom ui-widget-content"
         aria-hidden="false">

        <template id="user-group-selector">
            <div class="list-filter-form">
            <label for="user-list-group"><?php echo jLocale::get('jacl2db_admin~acl2.filter.group'); ?></label>
            <select name="grpid" id="user-list-group" class="user-list-group">
                <?php foreach($t->_vars['groups'] as $t->_vars['group']):?>

                    <option value="<?php echo $t->_vars['group']->id_aclgrp; ?>" <?php if($t->_vars['group']->id_aclgrp == $t->_vars['grpid']):?>selected="selected"<?php endif;?>><?php echo $t->_vars['group']->name; ?></option>
                <?php endforeach;?>

            </select>
            </div>
        </template>
        <template id="user-item-links">
            <a href="" class="user-rights-link  ui-button"><?php echo jLocale::get('jacl2db_admin~acl2.rights.link'); ?></a>
        </template>
        <table id="users-list"
               data-processing="true"
               data-server-side="true"
               data-page-length="15"
               data-length-menu="[ 10, 15, 20, 50, 80, 100 ]"
               data-jelix-url="<?php jtpl_function_html_jurl( $t,'jacl2db_admin~users:usersList' );?>">
            <thead>
            <tr>
                <th data-searchable="true" data-data="login"><?php echo jLocale::get('jacl2db_admin~acl2.col.users.login'); ?></th>
                <th data-orderable="false" data-data="groups"><?php echo jLocale::get('jacl2db_admin~acl2.col.groups'); ?></th>
                <th data-data="links" data-orderable="false" data-type="html"></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>


<?php 
}
return true;}
