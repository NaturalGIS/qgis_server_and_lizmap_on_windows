<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap/app/themes/default/jauthdb_admin/crud_edit.tpl') > 1696349194){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\vendor\jelix\jelix\lib\jelix\plugins\tpl/html/function.jurl.php');
function template_meta_4000682427be3ac0a45747745824d99a($t){
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions( $t->_vars['formOptions']);
    $builder->outputMetaContent($t);}
if(isset($t->_vars['form'])) { $builder = $t->_vars['form']->getBuilder( 'htmlbootstrap');
    $builder->setOptions( $t->_vars['formOptions']);
    $builder->outputMetaContent($t);}

}
function template_4000682427be3ac0a45747745824d99a($t){
?><?php if($t->_vars['id'] === null):?>

<h1><?php echo jLocale::get('jauthdb_admin~crud.title.create'); ?></h1>
<?php  $formTplController = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['form'], 'htmlbootstrap', $t->_vars['formOptions'], 'default:savecreate', array());$formTplController->startForm();$formTplController->outputAllControls();$formTplController->endForm();?>

    <?php if($t->_vars['randomPwd']):?>
        <p><?php echo jLocale::get('jauthdb_admin~crud.form.random.password'); ?> <?php echo $t->_vars['randomPwd']; ?></p>
    <?php endif;?>


    <?php foreach($t->_vars['otherInfo'] as $t->_vars['info']):?>
        <?php echo $t->_vars['info']; ?>

    <?php endforeach;?>

    <p><a href="<?php jtpl_function_html_jurl( $t,'default:index');?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.return.to.list'); ?></a></p>

<?php else:?>

<h1><?php echo jLocale::get('jauthdb_admin~crud.title.update'); ?></h1>

<?php  $formTplController = new \jelix\forms\HtmlWidget\TemplateController($t->_vars['form'], 'htmlbootstrap', $t->_vars['formOptions'], 'default:saveupdate', array('j_user_login'=>$t->_vars['id']));$formTplController->startForm();$formTplController->outputAllControls();$formTplController->endForm();?>


    <?php foreach($t->_vars['otherInfo'] as $t->_vars['info']):?>
        <?php echo $t->_vars['info']; ?>

    <?php endforeach;?>

    <p><a href="<?php jtpl_function_html_jurl( $t,'default:view', array('j_user_login'=>$t->_vars['id']));?>" class="crud-link btn"><?php echo jLocale::get('jauthdb_admin~crud.link.return.to.view'); ?></a>.</p>
<?php endif;?>



<?php 
}
return true;}
