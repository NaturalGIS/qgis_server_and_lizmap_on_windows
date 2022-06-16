<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/templates/user_menu.tpl') > 1620802989){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.hook.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_3bd4f50e13d9cb8a685574f20877d8f3($t){

}
function template_3bd4f50e13d9cb8a685574f20877d8f3($t){
?><?php jtpl_function_html_hook( $t,'LizTopMenuHtmlItems');?>

<?php  if(jAuth::isConnected()):?>
    <li class="dashboard-item"><a href="<?php jtpl_function_html_jurl( $t,'master_admin~default:index');?>">
            <span class="icon dashboard-icon"></span> <span class="text hidden-phone"><?php echo jLocale::get('view~default.header.menuitem.admin.label'); ?></span></a>
    </li>
    <li class="user dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="info-user">
            <span class="icon"></span>
            <span class="text hidden-phone">
                <span id="info-user-login" title="<?php echo $t->_vars['user']->firstname; ?> <?php echo $t->_vars['user']->lastname; ?>"><?php echo htmlspecialchars($t->_vars['user']->login); ?></span>
                <span style="display:none" id="info-user-firstname"><?php echo $t->_vars['user']->firstname; ?></span>
                <span style="display:none" id="info-user-lastname"><?php echo $t->_vars['user']->lastname; ?></span>
            </span>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
            <?php  if(jAcl2::check('auth.user.view')):?>
                <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show', array('user'=>$t->_vars['user']->login));?>"><?php echo jLocale::get('master_admin~gui.header.your.account'); ?></a></li>
            <?php  endif; ?>
            <?php jtpl_function_html_hook( $t,'LizAccountMenuHtmlItems');?>
            <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:out');?>?auth_url_return=<?php jtpl_function_html_jurl( $t,'view~default:index');?>"><?php echo jLocale::get('view~default.header.disconnect'); ?></a></li>
        </ul>
    </li>
<?php else:?>
    <li class="login">
        <?php if(isset($t->_vars['auth_url_return'])):?>
        <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:index', array('auth_url_return'=>$t->_vars['auth_url_return']));?>">
            <?php else:?>
        <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:index');?>">
        <?php endif;?>
            <span class="icon"></span>
            <span class="text hidden-phone"><?php echo jLocale::get('view~default.header.connect'); ?></span>
        </a>
    </li>
    <?php if(isset($t->_vars['allowUserAccountRequests']) and $t->_vars['allowUserAccountRequests'] == '1'):?>

        <li class="registered">
            <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~registration:index');?>">
                <span class="icon"></span>
                <span class="text hidden-phone"><?php echo jLocale::get('view~default.header.createAccount'); ?></span>
            </a>
        </li>
    <?php endif;?>
<?php  endif; ?>
<?php 
}
return true;}
