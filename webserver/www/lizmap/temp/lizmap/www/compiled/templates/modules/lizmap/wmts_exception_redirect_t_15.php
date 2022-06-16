<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/templates/wmts_exception.tpl') > 1616160726){ return false;
} else {
function template_meta_89dfaa2878ac743884d7c9217bb86fc9($t){

}
function template_89dfaa2878ac743884d7c9217bb86fc9($t){
?><ows:ExceptionReport xmlns:ows="http://www.opengis.net/ows/1.1" 
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
	xsi:schemaLocation="http://www.opengis.net/ows/1.1 http://schemas.opengis.net/ows/1.1.0/owsExceptionReport.xsd" 
	version="1.0.0" xml:lang="en">
<?php foreach($t->_vars['messages'] as $t->_vars['type_msg'] => $t->_vars['all_msg']):?>
	<ows:Exception<?php if($t->_vars['type_msg'] != 'default'):?> exceptionCode="<?php echo $t->_vars['type_msg']; ?>"<?php endif;?>>
    <?php foreach($t->_vars['all_msg'] as $t->_vars['msg']):?>
		<ows:ExceptionText><?php echo $t->_vars['msg']; ?></ows:ExceptionText>
    <?php endforeach;?>

	</ows:Exception>
<?php endforeach;?>
</ows:ExceptionReport>
<?php 
}
return true;}
