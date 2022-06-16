<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_geobookmark.tpl') > 1616160726){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jmessage.php');
function template_meta_240a94ccb073e250a72f504e19355e3e($t){

}
function template_240a94ccb073e250a72f504e19355e3e($t){
?>    <?php jtpl_function_html_jmessage( $t);?>
    <?php if($t->_vars['gbList'] ):?>
      <div id="geobookmark-title">
      <?php echo jLocale::get('view~map.permalink.geobookmark.title'); ?>

      </div>
      <div>
        <?php if($t->_vars['gbCount'] > 0 ):?>

        <table class="table table-condensed table-stipped">
          <?php foreach($t->_vars['gbList'] as $t->_vars['gb']):?>
          <tr>
            <td><?php echo $t->_vars['gb']->name; ?></td>
            <td>
              <button class="btn-geobookmark-del btn btn-mini" value="<?php echo $t->_vars['gb']->id; ?>" title="<?php echo jLocale::get('view~map.permalink.geobookmark.button.del'); ?>"><i class="icon-remove"></i></button>
              <button class="btn-geobookmark-run btn btn-mini" value="<?php echo $t->_vars['gb']->id; ?>" title="<?php echo jLocale::get('view~map.permalink.geobookmark.button.run'); ?>"><i class="icon-zoom-in"></i></button>
            </td>
          </tr>
          <?php endforeach;?>

        </table>

        <?php else:?>
          <div id="geobookmark-none">
          <?php echo jLocale::get('view~map.permalink.geobookmark.none'); ?>

          </div>
        <?php endif;?>

      </div>

    <?php endif;?>
<?php 
}
return true;}
