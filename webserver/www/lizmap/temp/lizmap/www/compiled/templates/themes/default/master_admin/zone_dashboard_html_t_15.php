<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/var/themes/default/master_admin/zone_dashboard.tpl') > 1639502638){ return false;
} else {
function template_meta_58e22c5f2cd5cdb82d7d7846ac843201($t){

}
function template_58e22c5f2cd5cdb82d7d7846ac843201($t){
?>
<h1><?php echo jLocale::get('gui.dashboard.title'); ?></h1>
<?php if(!count($t->_vars['widgets'])):?>

    <p><?php echo jLocale::get('gui.dashboard.nowidget'); ?>.</p>
<?php else:?>


<?php $t->_vars['nbPerCol'] = ceil(count($t->_vars['widgets'])/2);?>
<div id="dashboard-content" class="row">
    <div id="dashboard-left-column" class="span6">
        <?php for($t->_vars['i']=0; $t->_vars['i']<$t->_vars['nbPerCol'];$t->_vars['i']++):?>
        <div class="dashboard-widget well">
            <h3><?php echo htmlspecialchars($t->_vars['widgets'][$t->_vars['i']]->title); ?></h3>
            <div class="dashboard-widget-content"><?php echo $t->_vars['widgets'][$t->_vars['i']]->content; ?></div>
        </div>
        <?php endfor;?>

    </div>
    <div id="dashboard-right-column" class="span6">
        <?php for($t->_vars['i']=$t->_vars['nbPerCol']; $t->_vars['i']<count($t->_vars['widgets']);$t->_vars['i']++):?>
        <div class="dashboard-widget well">
            <h3><?php echo htmlspecialchars($t->_vars['widgets'][$t->_vars['i']]->title); ?></h3>
            <div class="dashboard-widget-content"><?php echo $t->_vars['widgets'][$t->_vars['i']]->content; ?></div>
        </div>
        <?php endfor;?>

    </div>
</div>
<?php endif;?><?php 
}
return true;}
