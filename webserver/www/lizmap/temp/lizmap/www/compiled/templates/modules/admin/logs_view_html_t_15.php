<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/logs_view.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_eb3c49ae6448678a3a819ab9aa5511b5($t){

}
function template_eb3c49ae6448678a3a819ab9aa5511b5($t){
?><?php jtpl_function_html_jmessage_bootstrap( $t);?>

  <h1><?php echo jLocale::get('admin~admin.menu.lizmap.logs.label'); ?></h1>

  <div>
    <h2><?php echo jLocale::get('admin~admin.logs.counter.title'); ?></h2>

    <?php if(!$t->_vars['counterNumber']):?>

      <?php $t->_vars['counterNumber'] = 0;?>
    <?php endif;?>
    <?php echo $t->_vars['counterNumber']; ?> <?php echo jLocale::get('admin~admin.logs.counter.number.sentence'); ?>


    <div class="form-actions">
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:counter');?>"><?php echo jLocale::get('admin~admin.logs.view.button'); ?></a>
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:emptyCounter');?>" onclick="return confirm(`<?php echo jLocale::get('admin~admin.logs.empty.confirm'); ?>`)"><?php echo jLocale::get('admin~admin.logs.empty.button'); ?></a>
    </div>

  </div>

  <div>
    <h2><?php echo jLocale::get('admin~admin.logs.detail.title'); ?></h2>

    <?php if(!$t->_vars['detailNumber']):?>
      <?php $t->_vars['detailNumber'] = 0;?>
    <?php endif;?>
    <?php echo $t->_vars['detailNumber']; ?> <?php echo jLocale::get('admin~admin.logs.detail.number.sentence'); ?>


    <div class="form-actions">
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:detail');?>"><?php echo jLocale::get('admin~admin.logs.view.button'); ?></a>
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:export');?>"><?php echo jLocale::get('admin~admin.logs.export.button'); ?></a>
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:emptyDetail');?>" onclick="return confirm(`<?php echo jLocale::get('admin~admin.logs.empty.confirm'); ?>`)"><?php echo jLocale::get('admin~admin.logs.empty.button'); ?></a>
    </div>

  </div>


  <div>
    <h2><?php echo jLocale::get('admin~admin.logs.error.title'); ?></h2>

    <p><?php echo jLocale::get('admin~admin.logs.error.sentence'); ?></p>

<textarea rows="10" style="width:90%;"><?php if($t->_vars['errorLog'] != 'toobig'):?><?php echo $t->_vars['errorLog']; ?><?php else:?><?php echo jLocale::get('admin~admin.logs.error.file.too.big'); ?><?php endif;?></textarea>

    <div class="form-actions">
      <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~logs:eraseError');?>" onclick="return confirm(`<?php echo jLocale::get('admin~admin.logs.error.file.erase.confirm'); ?>`);"><?php echo jLocale::get('admin~admin.logs.error.file.erase'); ?></a>
    </div>
  </div>
<?php 
}
return true;}
