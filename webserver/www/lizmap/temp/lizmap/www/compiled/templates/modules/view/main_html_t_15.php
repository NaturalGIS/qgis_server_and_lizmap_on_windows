<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/main.tpl') > 1648478290){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
function template_meta_f7a09b42d7caf4012d1646cd46be7ad2($t){
jtpl_meta_html_html( $t,'csstheme','css/main.css');
jtpl_meta_html_html( $t,'csstheme','css/media.css');
$t->meta('lizmap~user_menu');

}
function template_f7a09b42d7caf4012d1646cd46be7ad2($t){
?>


<div id="header">
  <div id="logo">
  </div>
  <div id="title">
    <h1><?php echo $t->_vars['repositoryLabel']; ?></h1>
  </div>

  <div id="headermenu" class="navbar navbar-fixed-top">
    <div id="auth" class="navbar-inner">
      <ul class="nav pull-right">
        <?php $t->display('lizmap~user_menu');?>

      </ul>
    </div>
  </div>
</div>

<div id="content" class="container">
  <div id="search">
    <div class="input-prepend">
      <button id="toggle-search" class="btn" type="button" data-toggle="tooltip"
        title="<?php echo jLocale::get('view~default.header.search.toggleKeywordsTitle.title'); ?>">T</button>
      <input id="search-project" class="span2" data-toggle="tooltip" title="<?php echo jLocale::get('view~default.header.search.input.title'); ?>"
        placeholder="<?php echo jLocale::get('view~map.search.nominatim.placeholder'); ?>" type="text">
    </div>
    <div id="search-project-keywords">
      <span id="search-project-keywords-selected"></span><span id="search-project-result"></span>
    </div>
  </div>
  <?php jtpl_function_html_jmessage_bootstrap( $t);?>

  <?php if(isset($t->_vars['landing_page_content'])):?>
  <div id="landingPageContent">
    <?php echo $t->_vars['landing_page_content']; ?>

  </div>
  <?php endif;?>
  <?php echo $t->_vars['MAIN']; ?>

  <footer class="footer">
    <p class="pull-right">
      <img src="<?php echo $t->_vars['j_themepath'].'css/img/logo_footer.png'; ?>" alt=""/>
    </p>
  </footer>
</div>

<?php if($t->_vars['googleAnalyticsID'] && $t->_vars['googleAnalyticsID'] != ''):?>

<!-- Google Analytics -->
<script type="text/javascript">

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $t->_vars['googleAnalyticsID']; ?>', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
<?php endif;?>

<?php 
}
return true;}
