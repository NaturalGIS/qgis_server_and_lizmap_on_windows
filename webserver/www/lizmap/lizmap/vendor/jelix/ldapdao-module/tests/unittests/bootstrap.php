<?php
require_once(__DIR__.'/../testapp/application.init.php');
require_once(LIB_PATH.'jelix-tests/classes/junittestcase.class.php');
require_once(LIB_PATH.'jelix-tests/classes/junittestcasedb.class.php');

define('TESTAPP_URL', 'http://ldapdao.local/');
define('TESTAPP_URL_HOST', 'ldapdao.local');
define('TESTAPP_URL_HOST_PORT', 'ldapdao.local');
define('TESTAPP_HOST', 'ldapdao.local');
define('TESTAPP_PORT', '');

define('TESTAPP_LDAP_HOST', 'openldap');


jApp::setEnv('jelixtests');
if (file_exists(jApp::tempPath())) {
    jAppManager::clearTemp(jApp::tempPath());
} else {
    jFile::createDir(jApp::tempPath(), intval("775",8));
}
