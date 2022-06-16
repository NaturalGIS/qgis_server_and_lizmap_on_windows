<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/view.tpl') > 1651224964){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/meta.html.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/common/modifier.truncate.php');
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/modifier.nl2br.php');
function template_meta_97ecf81651077e876942eb1c8b4df3b8($t){
jtpl_meta_html_html( $t,'csstheme','css/main.css');
jtpl_meta_html_html( $t,'csstheme','css/view.css');
jtpl_meta_html_html( $t,'csstheme','css/media.css');

}
function template_97ecf81651077e876942eb1c8b4df3b8($t){
?>



<span id="anchor-top-projects"></span>
<?php $t->_vars['idm'] = 0;?>
<?php foreach($t->_vars['mapitems'] as $t->_vars['mi']):?>
<?php if($t->_vars['mi']->type == 'rep'):?>
<h2 class="liz-repository-title"><?php echo $t->_vars['mi']->title; ?></h2>
<ul class="thumbnails liz-repository-project-list">
  <?php foreach($t->_vars['mi']->childItems as $t->_vars['p']):?>

  <?php $t->_vars['idm'] = $t->_vars['idm'] + 1;?>
  <a name="link-projet-<?php echo $t->_vars['idm']; ?>"></a>
  <li class="span3 liz-repository-project-item">
    <div class="thumbnail">
      <div class="liz-project">
        <img width="250" height="250" src="<?php echo $t->_vars['p']->img; ?>" alt="project image" class="liz-project-img">
        <p class="liz-project-desc" style="display:none;">
          <b class="title"><?php echo $t->_vars['p']->title; ?></b>
          <br/>
          <br/><b><?php echo jLocale::get('default.project.abstract.label'); ?></b>&nbsp;: <span class="abstract"><?php echo jtpl_modifier_common_truncate(strip_tags($t->_vars['p']->abstract),100); ?></span>
          <br/>
          <br/><b><?php echo jLocale::get('default.project.keywordList.label'); ?></b>&nbsp;: <span class="keywordList"><?php echo $t->_vars['p']->keywordList; ?></span>
          <br/>
          <br/><b><?php echo jLocale::get('default.project.projection.label'); ?></b>&nbsp;: <span class="proj"><?php echo $t->_vars['p']->proj; ?></span>
          <br/><b><?php echo jLocale::get('default.project.bbox.label'); ?></b>&nbsp;: <span class="bbox"><?php echo $t->_vars['p']->bbox; ?></span>
        </p>
      </div>
      <h5 class="liz-project-title"><?php echo $t->_vars['p']->title; ?></h5>
      <p style="text-align:center;">
        <a class="btn liz-project-view" href="<?php echo $t->_vars['p']->url; ?><?php if($t->_vars['hide_header']):?>&h=0<?php endif;?>"><?php echo jLocale::get('default.project.open.map'); ?></a>
        <a class="btn liz-project-show-desc" href="#link-projet-<?php echo $t->_vars['idm']; ?>" onclick="$('#liz-project-modal-<?php echo $t->_vars['idm']; ?>').modal('show'); return false;"><?php echo jLocale::get('default.project.open.map.metadata'); ?></a>
      </p>
    </div>

    <div id="liz-project-modal-<?php echo $t->_vars['idm']; ?>" class="modal fade hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-show="false" data-keyboard="false" data-backdrop="static">

      <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo $t->_vars['p']->title; ?></h3>
      </div>
      <div class="modal-body">
        <dl class="dl-horizontal">
          <dt><?php echo jLocale::get('view~map.metadata.h2.illustration'); ?></dt>
          <dd><img src="<?php echo $t->_vars['p']->img; ?>" alt="project image" width="150" height="150"></dd>
          <dt><?php echo jLocale::get('default.project.title.label'); ?></dt>
          <dd><?php echo $t->_vars['p']->title; ?>&nbsp;</dd>
          <dt><?php echo jLocale::get('default.project.abstract.label'); ?></dt>
          <dd><?php echo jtpl_modifier_html_nl2br($t->_vars['p']->abstract); ?>&nbsp;</dd>
          <dt><?php echo jLocale::get('default.project.projection.label'); ?></dt>
          <dd><span class="proj"><?php echo $t->_vars['p']->proj; ?></span>&nbsp;</dd>
          <dt><?php echo jLocale::get('default.project.bbox.label'); ?></dt>
          <dd><span class="bbox"><?php echo $t->_vars['p']->bbox; ?></span></dd>
          <?php if($t->_vars['p']->wmsGetCapabilitiesUrl):?>

          <dt><?php echo jLocale::get('view~map.metadata.properties.wmsGetCapabilitiesUrl'); ?></dt>
          <dd><small><a href="<?php echo $t->_vars['p']->wmsGetCapabilitiesUrl; ?>" target="_blank">WMS Url</a></small></dd>
          <dd><small><a href="<?php echo $t->_vars['p']->wmtsGetCapabilitiesUrl; ?>" target="_blank">WMTS Url</a></small></dd>
          <?php endif;?>

        </dl>
      </div>
      <div class="modal-footer">
        <a class="btn liz-project-view" href="<?php echo $t->_vars['p']->url; ?><?php if($t->_vars['hide_header']):?>&h=0<?php endif;?>"><?php echo jLocale::get('default.project.open.map'); ?></a>
        <a href="#" class="btn" data-dismiss="modal"><?php echo jLocale::get('default.project.close.map.metadata'); ?></a>
      </div>
    </div>
  </li>
  <?php endforeach;?>

</ul>
<?php endif;?>
<?php endforeach;?>
<?php 
}
return true;}
