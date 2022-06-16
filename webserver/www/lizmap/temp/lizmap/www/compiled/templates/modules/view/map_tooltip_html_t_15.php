<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_tooltip.tpl') > 1616160726){ return false;
} else {
function template_meta_c7a61b434e2bfd7dfbae1cf57cfc11b1($t){

}
function template_c7a61b434e2bfd7dfbae1cf57cfc11b1($t){
?><div class="tooltip-layer">
  <h3>
    <span class="title">
      <button class="btn-tooltip-layer-clear btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button>
      <button id="tooltip-cancel" class="btn-tooltip-cancel btn btn-mini btn-link" type="button"></button>
      <span class="icon"></span>
      <span class="text">&nbsp;<?php echo jLocale::get('view~map.tooltip.toolbar.title'); ?>&nbsp;</span>
    </span>
  </h3>
  <div class="menu-content">
    <table>
      <tr>
        <td><?php echo jLocale::get('view~map.tooltip.toolbar.layer'); ?></td>
      </tr>
      <tr>
        <td>
            <select id="tooltip-layer-list" class="btn-tooltip-layer-list loading" disabled="">
                <option value="">---</option>
            </select>
        </td>
      </tr>
    </table>
  </div>
</div>
<?php 
}
return true;}
