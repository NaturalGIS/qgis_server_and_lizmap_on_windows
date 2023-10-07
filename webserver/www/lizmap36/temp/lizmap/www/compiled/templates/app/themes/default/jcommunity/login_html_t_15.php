<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/jcommunity/login.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.hook.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_label.php');
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.ctrl_control.php');
function template_meta_8b233711e4d8eef91f58bb1e752b5528($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions(array());
    $builder->outputMetaContent($t);}

}
function template_8b233711e4d8eef91f58bb1e752b5528($t){
?><div id="auth_login_zone">

    <?php  if(jAuth::isConnected()):?>
        <p><?php echo htmlspecialchars($t->_vars['login'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>, <?php echo jLocale::get('jcommunity~login.startpage.connected'); ?></p>
        <div class="loginbox-links">
            (<a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:out');?>"><?php echo jLocale::get('jcommunity~login.logout'); ?></a>,
            <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show', array('user'=>$t->_vars['login']));?>"><?php echo jLocale::get('jcommunity~login.login.account'); ?></a>)
        </div>
        <?php jtpl_function_html_hook( $t,'JauthLoginFormExtraAuthenticated');?>

    <?php else:?>
    <?php jtpl_function_html_hook( $t,'JauthLoginFormExtraBefore');?>
    <?php  $t->_privateVars['__formTplController'] = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['form'], 'htmlbootstrap',array(), 'jcommunity~login:in', array());$t->_privateVars['__formTplController']->startForm();?>
        <fieldset>
            <div class="control-group">
                <?php jtpl_function_html_ctrl_label( $t,'auth_login');?>
                <div class="controls">
                    <?php jtpl_function_html_ctrl_control( $t,'auth_login');?>
                </div>
            </div>
            <div class="control-group">
                <?php jtpl_function_html_ctrl_label( $t,'auth_password');?>
                <div class="controls">
                    <?php jtpl_function_html_ctrl_control( $t,'auth_password');?>
                </div>
            </div>
            <?php if($t->_vars['persistance_ok']):?>
                <div class="control-group">
                    <div class="controls">
                        <?php jtpl_function_html_ctrl_control( $t,'auth_remember_me');?> <?php jtpl_function_html_ctrl_label( $t,'auth_remember_me');?>
                    </div>
                </div>
            <?php endif;?>
            <?php if($t->_vars['url_return']):?>
                <input type="hidden" name="auth_url_return" value="<?php echo htmlspecialchars($t->_vars['url_return'], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8"); ?>" />
            <?php endif;?>

            <div class="form-actions">
                <input type="submit" value="<?php echo jLocale::get('jcommunity~login.startpage.login'); ?>" class="btn"/>
            </div>
        </fieldset>
    <?php $t->_privateVars['__formTplController']->endForm();
unset($t->_privateVars['__formTplController']);?>


        <div class="loginbox-links">
            <?php if($t->_vars['canRegister']):?><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~registration:index');?>" class="loginbox-links-create"><?php echo jLocale::get('jcommunity~login.startpage.account.create'); ?></a><?php endif;?>
            <?php if($t->_vars['canResetPassword']):?><?php if($t->_vars['canRegister']):?><span class="loginbox-links-separator"> - </span><?php endif;?>
                <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~password_reset:index');?>" class="loginbox-links-resetpass"><?php echo jLocale::get('jcommunity~login.login.password.reset'); ?></a><?php endif;?>
        </div>
    <?php jtpl_function_html_hook( $t,'JauthLoginFormExtraAfter');?>
    <?php  endif; ?>
</div>
<?php 
}
return true;}
