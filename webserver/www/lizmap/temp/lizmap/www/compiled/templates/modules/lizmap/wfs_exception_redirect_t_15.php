<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/templates/wfs_exception.tpl') > 1616160726){ return false;
} else {
function template_meta_c473c72381f6a5e392e5fad30e8cf7e3($t){

}
function template_c473c72381f6a5e392e5fad30e8cf7e3($t){
?><ServiceExceptionReport version="1.2.0" xmlns="http://www.opengis.net/ogc" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opengis.net/ogc http://schemas.opengis.net/wfs/1.0.0/OGC-exception.xsd">
<?php foreach($t->_vars['messages'] as $t->_vars['type_msg'] => $t->_vars['all_msg']):?>
  <ServiceException<?php if($t->_vars['type_msg'] != 'default'):?> code="<?php echo $t->_vars['type_msg']; ?>"<?php endif;?>>
    <?php foreach($t->_vars['all_msg'] as $t->_vars['msg']):?>
    <?php echo $t->_vars['msg']; ?>

    <?php endforeach;?>
  </ServiceException>
<?php endforeach;?>
</ServiceExceptionReport>
<?php 
}
return true;}
