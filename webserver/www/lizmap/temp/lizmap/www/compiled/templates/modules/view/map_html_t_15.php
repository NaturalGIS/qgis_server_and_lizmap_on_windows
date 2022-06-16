<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lizmap/plugins/tpl/html/function.jmessage_bootstrap.php');
function template_meta_7c7216de7b3c0993c2dfd8a14c9f2d4a($t){

}
function template_7c7216de7b3c0993c2dfd8a14c9f2d4a($t){
?><div id="header">
  <div id="logo">
  </div>
  <div id="title">
    <h1>
    <?php if($t->_vars['WMSServiceTitle']):?>
      <?php echo $t->_vars['WMSServiceTitle']; ?>

    <?php else:?>
      <?php echo jLocale::get('view~map.title.h1'); ?>

    <?php endif;?>
    </h1>
    <h2><?php echo $t->_vars['repositoryLabel']; ?></h2>
  </div>
  <div id="headermenu" class="navbar navbar-fixed-top">
    <?php echo jZone::get('view~map_headermenu', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'auth_url_return'=>$t->_vars['auth_url_return']));?>

  </div>
</div>

<div id="content">

  <div id="menuToggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <div id="mapmenu">
    <?php echo jZone::get('view~map_menu', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'dockable'=>$t->_vars['dockable'],'minidockable'=>$t->_vars['minidockable'], 'bottomdockable'=>$t->_vars['bottomdockable'], 'rightdockable'=>$t->_vars['rightdockable']));?>
  </div>

  <div id="docks-wrapper">
    <div id="dock">
      <?php echo jZone::get('view~map_dock', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'dockable'=>$t->_vars['dockable']));?>
    </div>
    
    <div id="sub-dock">
    </div>
    
    <div id="bottom-dock" style="display:none;">
      <?php echo jZone::get('view~map_bottomdock', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'dockable'=>$t->_vars['bottomdockable']));?>
    </div>
    
    <div id="right-dock" style="display:none;">
      <?php echo jZone::get('view~map_rightdock', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'dockable'=>$t->_vars['rightdockable']));?>
    </div>
  </div>
  <div id="map-content">
    <div id="newOlMap" style="width:1px;height:1px;position: absolute;"></div>
    <div id="map"></div>

    <div id="mini-dock">
      <?php echo jZone::get('view~map_minidock', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project'],'dockable'=>$t->_vars['minidockable']));?>
    </div>

    <span id="navbar">
      <button class="btn pan active" title="<?php echo jLocale::get('view~map.navbar.pan.hover'); ?>"></button><br/>
      <button class="btn zoom" title="<?php echo jLocale::get('view~map.navbar.zoom.hover'); ?>"></button><br/>
      <button class="btn zoom-extent" title="<?php echo jLocale::get('view~map.navbar.zoomextent.hover'); ?>"></button><br/>
      <button class="btn zoom-in" title="<?php echo jLocale::get('view~map.navbar.zoomin.hover'); ?>"></button><br/>
      <div class="slider" title="<?php echo jLocale::get('view~map.navbar.slider.hover'); ?>"></div>
      <button class="btn zoom-out" title="<?php echo jLocale::get('view~map.navbar.zoomout.hover'); ?>"></button><br/>
      <span class="history">
        <button class="btn previous disabled" title="<?php echo jLocale::get('view~map.navbar.previous.hover'); ?>"></button>
        <button class="btn next disabled" title="<?php echo jLocale::get('view~map.navbar.next.hover'); ?>"></button>
      </span>
      <span id="zoom-in-max-msg" class="ui-widget-content ui-corner-all" style="display:none;"><?php echo jLocale::get('view~map.message.zoominmax'); ?></span>
    </span>

    <div id="overview-box">
      <div id="overview-map" title="<?php echo jLocale::get('view~map.overviewmap.hover'); ?>"></div>
      <div id="overview-bar">
       <lizmap-scaleline title="<?php echo jLocale::get('view~map.overviewbar.scaletext.hover'); ?>"></lizmap-scaleline>
        <button id="overview-toggle" class="btn" title="<?php echo jLocale::get('view~map.overviewbar.displayoverview.hover'); ?>"></button>
      </div>
      <lizmap-mouse-position></lizmap-mouse-position>
    </div>
    <div id="attribution-box">
      <span id="attribution"></span>
      <img src="<?php echo $t->_vars['j_themepath'].'css/img/logo_footer.png'; ?>" alt=""/>
    </div>

    <div id="message" class="span6"><?php jtpl_function_html_jmessage_bootstrap( $t);?></div>


    <div id="lizmap-search">

      <div id="lizmap-search-close">
        <button class="btn btn-mini btn-primary"><?php echo jLocale::get('view~map.bottomdock.toolbar.btn.clear.title'); ?></button>
      </div>

      <div>
        <ul class="items"></ul>
      </div>

    </div>

  </div>
</div>

<div id="loading" class="ui-dialog-content ui-widget-content" title="<?php echo jLocale::get('view~map.loading.title'); ?>">
  <p>
  </p>
</div>

<div id="lizmap-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-show="false" data-keyboard="false" data-backdrop="static">
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
