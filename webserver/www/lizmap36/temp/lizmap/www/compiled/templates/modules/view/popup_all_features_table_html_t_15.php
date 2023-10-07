<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\view\templates/popup_all_features_table.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap36\lizmap\plugins\tpl/common/modifier.featurepopup.php');
function template_meta_6f0948522561e7ba3f9b863c9ea53cfc($t){

}
function template_6f0948522561e7ba3f9b863c9ea53cfc($t){
?><div class='popupAllFeaturesCompact' style="display: none;">
    <h4><?php echo $t->_vars['layerTitle']; ?></h4>

    <table class='table table-condensed table-striped table-bordered lizmapPopupTable'>
        <thead>
            <?php foreach($t->_vars['allFeatureAttributes'] as $t->_vars['featureAttributes']):?>

            <tr>
                <?php foreach($t->_vars['featureAttributes'] as $t->_vars['attribute']):?>
                    <?php if($t->_vars['attribute']['name'] != 'geometry' && $t->_vars['attribute']['name'] != 'maptip' && $t->_vars['attribute']['value'] != ''):?>
                        <th><?php echo $t->_vars['attribute']['name']; ?></th>
                    <?php endif;?>

                <?php endforeach;?>
            </tr>
            <?php  break;?>
            <?php endforeach;?>
        </thead>

        <tbody>
            <?php foreach($t->_vars['allFeatureAttributes'] as $t->_vars['featureAttributes']):?>
                <tr>
                <?php foreach($t->_vars['featureAttributes'] as $t->_vars['attribute']):?>
                    <?php if($t->_vars['attribute']['name'] != 'geometry' && $t->_vars['attribute']['name'] != 'maptip' && $t->_vars['attribute']['value'] != ''):?>
                        <td><?php echo jtpl_modifier_common_featurepopup($t->_vars['attribute']['name'],$t->_vars['attribute']['value'],$t->_vars['repository'],$t->_vars['project']); ?></td>
                    <?php endif;?>

                <?php endforeach;?>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php 
}
return true;}
