<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/dataviz/templates/dataviz_dock.tpl') > 1648478290){ return false;
} else {
function template_meta_9b0bb68158f14f1ad3aa87cf271deca9($t){

}
function template_9b0bb68158f14f1ad3aa87cf271deca9($t){
?><div id="dataviz-container">
    <div id="dataviz-content" class="<?php echo $t->_vars['theme']; ?>">
    </div>
</div>
<?php 
}
return true;}
