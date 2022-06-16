<?php
/**
* @package   testapp
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

require_once (__DIR__.'/../application.init.php');

jApp::setEnv('install');

$installer = new jInstaller(new textInstallReporter());

if (!$installer->installApplication()) {
    exit(1);
}

try {
    jAppManager::clearTemp();    
}
catch(Exception $e) {
    echo "WARNING: temporary files cannot be deleted because of this error: ".$e->getMessage().".\nWARNING: Delete temp files by hand immediately!\n";
    exit(1);
}

exit(0);