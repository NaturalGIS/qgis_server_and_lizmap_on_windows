<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/maps.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_e7ec90e9581ff565c3da15d771d6bfd4($t){

}
function template_e7ec90e9581ff565c3da15d771d6bfd4($t){
?><?php jtpl_function_html_jmessage_bootstrap( $t);?>

<h1><?php echo jLocale::get('admin~admin.configuration.repository.label'); ?></h1>

<?php  if(jAcl2::check('lizmap.admin.repositories.view')):?>

    <!--Repositories-->

        <!--Add a repository-->
        <?php  if(jAcl2::check('lizmap.admin.repositories.create')):?>
            <div style="margin:20px 0px;">
                <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:createSection');?>"><?php echo jLocale::get('admin~admin.configuration.button.add.repository.label'); ?></a>
            </div>
        <?php  endif; ?>


        <?php foreach($t->_vars['repositories'] as $t->_vars['repo']):?>

            <legend><?php echo $t->_vars['repo']->getKey(); ?></legend>

            <dl><dt><?php echo jLocale::get('admin~admin.form.admin_section.data.label'); ?></dt>
                <dd>
                    <table class="table">
                        <?php $t->_vars['section'] = 'repository:'.$t->_vars['repo']->getKey();?>

                        <?php $t->_vars['properties'] = $t->_vars['repo']->getRepoProperties();?>
                        <?php foreach($t->_vars['properties'] as $t->_vars['prop']):?>
                            <tr>
                                <?php if($t->_vars['prop'] == 'path' && $t->_vars['rootRepositories'] != ''):?>
                                    <?php if(substr($t->_vars['repo']->getPath(), 0, strlen($t->_vars['rootRepositories'])) === $t->_vars['rootRepositories']):?>
                                        <?php $t->_vars['d'] = substr($t->_vars['repo']->getPath(), strlen($t->_vars['rootRepositories']));?>
                                        <th><?php echo jLocale::get('admin~admin.form.admin_section.repository.'.$t->_vars['prop'].'.label'); ?></th><td><?php echo $t->_vars['d']; ?></td>
                                    <?php endif;?>

                                <?php else:?>
                                    <th><?php echo jLocale::get('admin~admin.form.admin_section.repository.'.$t->_vars['prop'].'.label'); ?></th><td><?php echo $t->_vars['repo']->getData($t->_vars['prop']); ?></td>
                                <?php endif;?>

                            </tr>
                        <?php endforeach;?>
                    </table>
                </dd>
            </dl>

            <dl><dt><?php echo jLocale::get('admin~admin.form.admin_section.groups.label'); ?></dt>
                <dd>
                    <table class="table">
                        <?php foreach($t->_vars['subjects'] as $t->_vars['s']):?>

                            <?php if(property_exists($t->_vars['data'][$t->_vars['repo']->getKey()], $t->_vars['s'])):?>
                                <tr>
                                    <th><?php echo $t->_vars['labels'][$t->_vars['s']]; ?></th><td><?php echo $t->_vars['data'][$t->_vars['repo']->getKey()]->{$t->_vars['s']}; ?></td>
                                </tr>
                            <?php endif;?>

                        <?php endforeach;?>
                    </table>
                </dd>
            </dl>

            <div class="form-actions">
                <!-- View repository page -->
                <?php  if(jAcl2::check('lizmap.repositories.view', $t->_vars['repo']->getKey())):?>
                    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'view~default:index', array('repository'=>$t->_vars['repo']->getKey()));?>" target="_blank"><?php echo jLocale::get('admin~admin.configuration.button.view.repository.label'); ?></a>
                <?php  endif; ?>
                <!-- Modify -->
                <?php  if(jAcl2::check('lizmap.admin.repositories.update')):?>
                    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:modifySection', array('repository'=>$t->_vars['repo']->getKey()));?>"><?php echo jLocale::get('admin~admin.configuration.button.modify.repository.label'); ?></a>
                <?php  endif; ?>
                <!-- Remove -->
                <?php  if(jAcl2::check('lizmap.admin.repositories.delete')):?>
                    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:removeSection', array('repository'=>$t->_vars['repo']->getKey()));?>" onclick="return confirm(`<?php echo jLocale::get('admin~admin.configuration.button.remove.repository.confirm.label'); ?>`)"><?php echo jLocale::get('admin~admin.configuration.button.remove.repository.label'); ?></a>
                <?php  endif; ?>
                <?php  if(jAcl2::check('lizmap.admin.repositories.delete')):?>
                    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:removeCache', array('repository'=>$t->_vars['repo']->getKey()));?>" onclick="return confirm(`<?php echo jLocale::get('admin~admin.cache.button.remove.repository.cache.confirm.label'); ?>`)"><?php echo jLocale::get('admin~admin.cache.button.remove.repository.cache.label'); ?></a>
                <?php  endif; ?>
            </div>

        <?php endforeach;?>
<?php  endif; ?>

<!--Add a repository-->
<?php if(count($t->_vars['repositories'])):?>
    <?php  if(jAcl2::check('lizmap.admin.repositories.create')):?>
        <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~maps:createSection');?>"><?php echo jLocale::get('admin~admin.configuration.button.add.repository.label'); ?></a>
    <?php  endif; ?>
<?php endif;?>
<?php 
}
return true;}
