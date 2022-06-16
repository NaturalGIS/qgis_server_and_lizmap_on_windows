<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/admin/templates/theme.tpl') > 1616160726){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.ctrl_label.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.ctrl_value.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_7400e8b751d46ba20d1e054404fe5a00($t){

}
function template_7400e8b751d46ba20d1e054404fe5a00($t){
?>  <?php  if(jAcl2::check('lizmap.admin.services.view')):?>
  <!--Services-->
  <div>
    <h2><?php echo jLocale::get('admin.theme.detail.title'); ?></h2>
    <table class="table">
      <?php if(is_array($t->_vars['themeForm'])){
                $ctrls_to_display = $t->_vars['themeForm'];
                $ctrls_notto_display = null;
            }
            else {
                $t->_privateVars['__form'] = $t->_vars['themeForm'];
                $ctrls_to_display=null;
                $ctrls_notto_display = null;
            }
if (!isset($t->_privateVars['__formbuilder'])) {
    $t->_privateVars['__formViewMode'] = 1;
    $t->_privateVars['__formbuilder'] = $t->_privateVars['__form']->getBuilder('html');
}
if (!isset($t->_privateVars['__displayed_ctrl'])) {
    $t->_privateVars['__displayed_ctrl'] = array();
}
$t->_privateVars['__ctrlref']='';

foreach($t->_privateVars['__form']->getRootControls() as $ctrlref=>$ctrl){
    if(!$t->_privateVars['__form']->isActivated($ctrlref)) continue;
    if($ctrl->type == 'reset' || $ctrl->type == 'hidden') continue;
if($ctrl->type == 'submit' && $ctrl->standalone) continue;
            if($ctrl->type == 'captcha' || $ctrl->type == 'secretconfirm') continue;
if(!isset($t->_privateVars['__displayed_ctrl'][$ctrlref])
       && (  ($ctrls_to_display===null && $ctrls_notto_display === null)
          || ($ctrls_to_display===null && !in_array($ctrlref, $ctrls_notto_display))
          || (is_array($ctrls_to_display) && in_array($ctrlref, $ctrls_to_display) ))) {
        $t->_privateVars['__ctrlref'] = $ctrlref;
        $t->_privateVars['__ctrl'] = $ctrl;
?>

      <tr>
        <th><?php jtpl_function_html_ctrl_label( $t);?></th>
        <td>
            <?php jtpl_function_html_ctrl_value( $t);?>
        </td>
      </tr>
      <?php }} $t->_privateVars['__ctrlref']='';?>
    </table>

    <!-- Modify -->
    <?php  if(jAcl2::check('lizmap.admin.services.update')):?>
    <div class="form-actions">
    <a class="btn" href="<?php jtpl_function_html_jurl( $t,'admin~theme:modify');?>">
      <?php echo jLocale::get('admin~admin.configuration.button.modify.theme.label'); ?>
    </a>
    </div>
    <?php  endif; ?>
  </div>
  <?php  endif; ?>

<script>
    
    function confirmImageDelete(){
        return confirm("<?php echo jLocale::get('admin~admin.theme.button.remove.logo.confirm.label'); ?>");
    }
    
<?php foreach($t->_vars['hasHeaderImage'] as $t->_vars['item']=>$t->_vars['has']):?>
    <?php if($t->_vars['has']):?>
        
        $(document).ready(function() {
            // Replace theme image value by corresponding image
            var html = '<img src="<?php jtpl_function_html_jurl( $t,'view~media:themeImage', array('key'=>$t->_vars['item']));?>" style="max-width:200px;">';
            html+= '&nbsp;<a onclick="confirmImageDelete();" href="<?php jtpl_function_html_jurl( $t,'admin~theme:removeThemeImage', array('key'=>$t->_vars['item']));?>" class="btn" class="btn-remove-theme-image"><?php echo jLocale::get('admin~admin.theme.button.remove.logo.label'); ?></a>';
            $('#_<?php echo $t->_vars['item']; ?>').html( html );
        });
        

    <?php else:?>
        
        $(document).ready(function() {
            $( '#_<?php echo $t->_vars['item']; ?>').html('');
        });
        
    <?php endif;?>
<?php endforeach;?>
</script>

<?php 
}
return true;}
