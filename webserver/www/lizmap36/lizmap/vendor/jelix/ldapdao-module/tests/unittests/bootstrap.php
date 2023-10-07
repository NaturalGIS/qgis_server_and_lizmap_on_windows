<?php
require_once(__DIR__.'/../testapp/application.init.php');

define('TESTAPP_URL', 'http://ldapdao.local/');
define('TESTAPP_URL_HOST', 'ldapdao.local');
define('TESTAPP_URL_HOST_PORT', 'ldapdao.local');
define('TESTAPP_HOST', 'ldapdao.local');
define('TESTAPP_PORT', '');

define('TESTAPP_LDAP_HOST', 'ldap.jelix');


jApp::setEnv('jelixtests');
if (file_exists(jApp::tempPath())) {
    jAppManager::clearTemp(jApp::tempPath());
} else {
    jFile::createDir(jApp::tempPath(), intval("775",8));
}
