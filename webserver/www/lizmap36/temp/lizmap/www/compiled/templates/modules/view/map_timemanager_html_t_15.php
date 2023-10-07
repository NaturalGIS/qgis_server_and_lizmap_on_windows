<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\view\templates/map_timemanager.tpl') > 1648478290){ return false;
} else {
function template_meta_10c9e07e02fc5726295408b46153a7c8($t){

}
function template_10c9e07e02fc5726295408b46153a7c8($t){
?><div id="timemanager-menu" class="timemanager" style="display:none;">
    <h3>
        <span class="title">
            <button class="btn-timemanager-clear btn btn-mini btn-error btn-link" title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>">×</button>
            <span class="icon"></span>&nbsp;<?php echo jLocale::get('view~map.timemanager.toolbar.title'); ?>&nbsp;<span class="text"></span>
        </span>
    </h3>
    <div class="menu-content">
        <div id="tmSlider"></div>
        <div>
            <span id="tmCurrentValue"></span><span> - </span><span id="tmNextValue"></span><br/>
            <button id="tmPrev" class="btn-print-launch btn btn-small btn-primary"><?php echo jLocale::get('view~map.timemanager.toolbar.prev'); ?></button>
            <button id="tmTogglePlay" class="btn-print-launch btn btn-small btn-primary"><?php echo jLocale::get('view~map.timemanager.toolbar.play'); ?></button>
            <button id="tmNext" class="btn-print-launch btn btn-small btn-primary"><?php echo jLocale::get('view~map.timemanager.toolbar.next'); ?></button>
        </div>
        <div id="tmLayers"></div>
    </div>
</div>
<?php 
}
return true;}
