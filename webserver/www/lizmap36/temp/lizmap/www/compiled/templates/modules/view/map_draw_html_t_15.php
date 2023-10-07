<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap36\lizmap\modules\view\templates/map_draw.tpl') > 1673621956){ return false;
} else {
function template_meta_7ff0efae5570902a0392710c0d0c4372($t){

}
function template_7ff0efae5570902a0392710c0d0c4372($t){
?><div class="draw">
    <h3>
        <span class="title">
            <button class="btn-draw-clear btn btn-mini btn-error btn-link"
                title="<?php echo jLocale::get('view~map.toolbar.content.stop'); ?>" onclick="document.querySelector('#button-draw').click();">×</button>
            <svg>
                <use xlink:href="#pencil"></use>
            </svg>
            <span class="text">&nbsp;<?php echo jLocale::get('view~map.draw.navbar.title'); ?>&nbsp;</span>
        </span>
    </h3>

    <div class="menu-content">
        <lizmap-digitizing save import-export></lizmap-digitizing>
    </div>
</div>
<?php 
}
return true;}
