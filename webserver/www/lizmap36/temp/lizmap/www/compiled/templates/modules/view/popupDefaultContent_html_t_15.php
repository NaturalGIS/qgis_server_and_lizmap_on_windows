<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\view\templates/popupDefaultContent.tpl') > 1620802989){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/common/modifier.featurepopup.php');
function template_meta_25392ce48c8238db891932d3a8ff2c5d($t){

}
function template_25392ce48c8238db891932d3a8ff2c5d($t){
?><table class="lizmapPopupTable">
  <thead>
    <tr>
      <th><?php echo jLocale::get('view~map.popup.table.th.data'); ?></th>
      <th><?php echo jLocale::get('view~map.popup.table.th.value'); ?></th>
    </tr>
  </thead>

  <tbody>
  <?php foreach($t->_vars['attributes'] as $t->_vars['attribute']):?>

    <?php if($t->_vars['attribute']['name'] != 'geometry' && $t->_vars['attribute']['name'] != 'maptip'):?>
      <tr <?php if($t->_vars['attribute']['value']=='' || $t->_vars['attribute']['value']=='NULL' ):?> class="empty-data" <?php endif;?>>
        <th><?php echo $t->_vars['attribute']['name']; ?></th>
        <td><?php echo jtpl_modifier_common_featurepopup($t->_vars['attribute']['name'],$t->_vars['attribute']['value'],$t->_vars['repository'],$t->_vars['project']); ?></td>
      </tr>
    <?php endif;?>
  <?php endforeach;?>
  </tbody>
</table>
<?php 
}
return true;}
