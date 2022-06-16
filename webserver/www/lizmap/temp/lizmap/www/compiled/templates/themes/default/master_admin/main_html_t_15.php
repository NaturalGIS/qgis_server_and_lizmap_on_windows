<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/var/themes/default/master_admin/main.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
function template_meta_4fe55c8f9265a8f7dce6a80ac827762e($t){
jtpl_meta_html_html( $t,'jquery_ui','theme');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/bootstrap.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/bootstrap-responsive.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/main.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/admin.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/media.css');
jtpl_meta_html_html( $t,'csstheme','css/main.css');
jtpl_meta_html_html( $t,'csstheme','css/admin.css');
jtpl_meta_html_html( $t,'csstheme','css/media.css');

}
function template_4fe55c8f9265a8f7dce6a80ac827762e($t){
?>











<div id="header">
  <div id="logo">
  </div>
  <div id="title">
    <h1><?php echo jLocale::get('admin~admin.header.admin'); ?></h1>
  </div>
  <div id="headermenu" class="navbar navbar-fixed-top">
   <div id="auth" class="navbar-inner"><?php echo $t->_vars['INFOBOX']; ?></div>
  </div>
</div>

<div id="content" class="container-fluid">
  <div class="row-fluid">
    <div id="menu" class="span3">
      <div class="well sidebar-nav">
        <ul class="nav nav-list">
         <?php echo $t->_vars['MENU']; ?>

        </ul>
      </div>
    </div>
    <div class="span9">
      <div class="row-fluid">
        <div id="admin-message"><?php jtpl_function_html_jmessage_bootstrap( $t);?></div>
       <?php echo $t->_vars['MAIN']; ?>
      </div>
    </div>
  </div>
  <footer class="footer">
    <p class="pull-right">
      <img src="<?php echo $t->_vars['j_themepath'].'css/img/logo_footer.png'; ?>" alt=""/>
    </p>
  </footer>
</div>
<?php 
}
return true;}
