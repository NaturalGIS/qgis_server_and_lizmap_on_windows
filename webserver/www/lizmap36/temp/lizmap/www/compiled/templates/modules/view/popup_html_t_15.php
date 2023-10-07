<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\view\templates/popup.tpl') > 1673621956){ return false;
} else {
function template_meta_03e481a04d94d7bce3983d4fdc058739($t){

}
function template_03e481a04d94d7bce3983d4fdc058739($t){
?><div class="lizmapPopupSingleFeature">
    <h4 class="lizmapPopupTitle"><?php echo $t->_vars['layerTitle']; ?></h4>

    <div class="lizmapPopupDiv">
    <?php echo $t->_vars['popupContent']; ?>

    </div>
</div>
<?php 
}
return true;}
