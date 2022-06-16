<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_permalink.tpl') > 1616160726){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/html/function.jfullurl.php');
function template_meta_6b846a2776cc312af10b7c1a6842faad($t){

}
function template_6b846a2776cc312af10b7c1a6842faad($t){
?><div class="permaLink">
  <h3>
    <span class="title">
      <button class="btn-permalink-clear btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button>
      <span class="icon"></span>
      <span class="text">&nbsp;<?php echo jLocale::get('view~map.permalink.toolbar.title'); ?>&nbsp;</span>
    </span>
  </h3>

  <div class="menu-content">
    <div id="permalink-box" class="tabbable">
        <ul class="nav nav-tabs permalink-tabs">
            <li class="active">
                <a href="#tab-share-permalink" data-toggle="tab" title="<?php echo jLocale::get('view~map.permalink.share.tab.title'); ?>"><?php echo jLocale::get('view~map.permalink.share.tab'); ?></a>
            </li>
            <li>
                <a href="#tab-embed-permalink" data-toggle="tab" title="<?php echo jLocale::get('view~map.permalink.embed.tab.title'); ?>"><?php echo jLocale::get('view~map.permalink.embed.tab'); ?></a>
            </li>
        </ul>
        <div class="tab-content permalink-tab-content">
            <div id="tab-share-permalink" class="permalink-tab-pane-share tab-pane active">
                <input id="input-share-permalink" type="text">
                <a href="" target="_blank" id="permalink" title="<?php echo jLocale::get('view~map.permalink.share.link'); ?>"><i class="icon-share"></i></a>
            </div>
            <div id="tab-embed-permalink" class="permalink-tab-pane-embed tab-pane">
                <a href="<?php jtpl_function_html_jfullurl( $t,'view~embed:index', array('repository'=>$t->_vars['repository'],'project'=>$t->_vars['project']));?>" target="_blank" id="permalink-embed" style="display:none;"></a>
                <select id="select-embed-permalink" class="permalink-embed-select" style="width:auto;">
                    <option value="s"><?php echo jLocale::get('view~map.permalink.embed.size.small'); ?></option>
                    <option value="m"><?php echo jLocale::get('view~map.permalink.embed.size.medium'); ?></option>
                    <option value="l"><?php echo jLocale::get('view~map.permalink.embed.size.large'); ?></option>
                    <option value="p"><?php echo jLocale::get('view~map.permalink.embed.size.personalized'); ?></option>
                </select>
                <span id="span-embed-personalized-permalink" class="permalink-personalized" style="display:none;">
                  <input id="input-embed-width-permalink" type="text" value="800">
                  <pan>×</pan>
                  <input id="input-embed-height-permalink" type="text" value="600">
                </span>
                <br/>
                <input id="input-embed-permalink" class="permalink-embed-input" type="text">
            </div>
        </div>
    </div>

    <?php if($t->_vars['gbContent']):?>

    <br/>
    <div id="geobookmark-container">
      <?php echo $t->_vars['gbContent']; ?>

    </div>

    <div>
      <form id="geobookmark-form">
        <input type="text" name="bname" placeholder="<?php echo jLocale::get('view~map.permalink.geobookmark.name.placeholder'); ?>">
        <input type="submit" class="btn-geobookmark-add btn btn-mini" title="<?php echo jLocale::get('view~map.permalink.geobookmark.button.add'); ?>" value="<?php echo jLocale::get('view~map.permalink.geobookmark.button.add'); ?>"/>
      </form>
    </div>
    <?php endif;?>


  </div>
</div>
<?php 
}
return true;}
