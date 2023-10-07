<?php
$appPath = __DIR__.'/';
require ($appPath.'vendor/autoload.php');

jApp::initPaths(
    $appPath
    //$appPath.'www/',
    //$appPath.'var/',
    //$appPath.'var/log/',
    //$appPath.'var/config/'
);
jApp::setTempBasePath(realpath($appPath.'../temp/').'/');

require ($appPath.'vendor/jelix_app_path.php');

jApp::declareModulesDir(array(
    __DIR__.'/modules/'
));
jApp::declareModule(__DIR__.'/../../ldapdao');
