<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/templates/wms_exception.tpl') > 1616160726){ return false;
} else {
function template_meta_42b20b4bce3b2b3b0d25ee97c973c119($t){

}
function template_42b20b4bce3b2b3b0d25ee97c973c119($t){
?><ServiceExceptionReport version="1.3.0" xmlns="http://www.opengis.net/ogc" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opengis.net/ogc http://schemas.opengis.net/wms/1.3.0/exceptions_1_3_0.xsd">
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
