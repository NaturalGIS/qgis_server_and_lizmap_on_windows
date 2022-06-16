<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/view/templates/map_print.tpl') > 1616160726){ return false;
} else {
function template_meta_60362883ab2a16c69a3a2eabfa00a6af($t){

}
function template_60362883ab2a16c69a3a2eabfa00a6af($t){
?><div class="print">
  <h3><span class="title"><button class="btn-print-clear btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button><span class="icon"></span>&nbsp;<?php echo jLocale::get('view~map.print.toolbar.title'); ?>&nbsp;<span class="text"></span></span></span></h3>
  <div class="menu-content">
    <table id="print-parameters" class="table table-condensed">
      <tr>
        <td><?php echo jLocale::get('view~map.print.toolbar.template'); ?></td>
        <td><?php echo jLocale::get('view~map.print.toolbar.scale'); ?></td>
        <td><?php echo jLocale::get('view~map.print.toolbar.dpi'); ?></td>
      </tr>
      <tr>
        <td><select id="print-template" class="btn-print-templates"></select></td>
        <td><select id="print-scale" class="btn-print-scales"></select></td>
        <td>
          <select id="print-dpi" class="btn-print-dpis">
            <option>100</option>
            <option>200</option>
            <option>300</option>
          </select>
        </td>
      </tr>
    </table>
    <div class="print-labels">
      <input type="text" class="print-label"><br>
      <textarea class="print-label"></textarea>
    </div>
    <div class="row-fluid">
      <div class="span4">
        <select id="print-format" title="<?php echo jLocale::get('view~map.print.toolbar.format'); ?>" class="btn-print-format">
          <option value="pdf">PDF</option>
          <option value="jpg">JPG</option>
          <option value="png">PNG</option>
          <option value="svg">SVG</option>
        </select>
      </div>
      <div class="span8">
        <button id="print-launch" class="btn-print-launch btn btn-small btn-primary btn-block"><span class="icon"></span>&nbsp;<?php echo jLocale::get('view~map.print.toolbar.title'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php 
}
return true;}
