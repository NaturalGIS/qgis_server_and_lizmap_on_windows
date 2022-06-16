<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/var/themes/default/master_admin/index_login.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_f22a05d51ef5a6517fb51a5f0b0c18f8($t){
jtpl_meta_html_html( $t,'jquery_ui','default');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/bootstrap.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/bootstrap-responsive.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/main.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/admin.css');
jtpl_meta_html_html( $t,'css',$t->_vars['j_basepath'].'assets/css/media.css');
jtpl_meta_html_html( $t,'csstheme','css/main.css');
jtpl_meta_html_html( $t,'csstheme','css/admin.css');
jtpl_meta_html_html( $t,'csstheme','css/media.css');
jtpl_meta_html_html( $t,'js',$t->_vars['j_basepath'].'assets/js/bootstrap.js');

}
function template_f22a05d51ef5a6517fb51a5f0b0c18f8($t){
?>













<div id="header">
  <div id="logo">
  </div>
  <div id="title">
    <h1><?php echo jLocale::get('jcommunity~login.login.title'); ?></h1>
  </div>

  <div id="headermenu" class="navbar navbar-fixed-top">
    <div id="auth" class="navbar-inner">
      <ul class="nav pull-right">
        <li class="home">
          <a href="<?php jtpl_function_html_jurl( $t,'view~default:index');?>" rel="tooltip" data-original-title="<?php echo jLocale::get('view~default.repository.list.title'); ?>" data-placement="bottom" href="#">
            <span class="icon"></span>
            <span class="text"><b><?php echo jLocale::get('view~default.repository.list.title'); ?></b></span>
          </a>
        </li>
    </div>
  </div>

</div>
<div id="content" class="container">
  <div class="row">
    <div>
       <?php echo $t->_vars['MAIN']; ?>

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
