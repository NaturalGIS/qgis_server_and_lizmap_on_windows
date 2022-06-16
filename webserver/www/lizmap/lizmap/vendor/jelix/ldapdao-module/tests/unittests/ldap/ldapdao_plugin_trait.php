<?php
/**
 * @package     ldapdao
 * @author      laurent Jouanneau
 * @copyright   2017 laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * common tests for the ldapdao plugin
 * @package     ldapdao
 */
trait ldapdao_plugin_trait {

    protected $config;

    protected $listenersBackup;

    function setUp(){
        parent::setUp();
        self::initClassicRequest(TESTAPP_URL.'index.php');
        jApp::pushCurrentModule('testapp');

        jProfiles::createVirtualProfile('ldap', $this->ldapProfileName, array(
            'hostname'=>TESTAPP_LDAP_HOST,
            'port'=> $this->ldapPort,
            'tlsMode'=> $this->ldapTlsMode,
            'adminUserDn'=>"cn=admin,dc=tests,dc=jelix",
            'adminPassword'=>"passjelix"
        ));

        $dir = __DIR__.'/../../../ldapdao/plugins/auth/';
        jApp::config()->_allBasePath[] = $dir;
        jApp::config()->_pluginsPathList_auth['ldapdao'] = $dir.'ldapdao/';

        $conf = parse_ini_file(__DIR__.'/authldap.coord.ini',true);
        $conf['ldapdao']['ldapprofile'] = $this->ldapProfileName;

        jAuth::loadConfig($conf);

        require_once( JELIX_LIB_PATH.'plugins/coord/auth/auth.coord.php');
        jApp::coord()->plugins['auth'] = new AuthCoordPlugin($conf);
        $this->config = & jApp::coord()->plugins['auth']->config;
        $_SESSION[$this->config['session_name']] = new jAuthDummyUser();

        // disable listener of jacl2db so testldap could be remove without
        // verifying if there is still an admin
        $this->listenersBackup = jApp::config()->disabledListeners;
        jApp::config()->disabledListeners['AuthCanRemoveUser'] = 'jacl2db~jacl2db';
        jEvent::clearCache();
        $cacheFile = jApp::tempPath('compiled/'.jApp::config()->urlengine['urlScriptId'].'.events.php');
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    function tearDown(){
        jApp::popCurrentModule();
        unset(jApp::coord()->plugins['auth']);
        unset($_SESSION[$this->config['session_name']]);
        $this->config = null;
        jApp::config()->disabledListeners = $this->listenersBackup;
        jEvent::clearCache();
        $cacheFile = jApp::tempPath('compiled/'.jApp::config()->urlengine['urlScriptId'].'.events.php');
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
    public function testEmptyUsersList()
    {
        $records = jAuth::getUserList();
        $myUsersLDAP = array();
        foreach ($records as $rec) {
            $myUsersLDAP[] = $rec;
        }
        $this->assertEquals(1, count($myUsersLDAP));
        $this->assertEquals('admin', $myUsersLDAP[0]->login);
        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('john') as $group) {
            $groups[] = $group;
        }
        $this->assertEquals(array(), $groups);
        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('jane') as $group) {
            $groups[] = $group;
        }
        $this->assertEquals(array(), $groups);
    }

    public function testLogin() {
        //$this->assertFalse(jAuth::verifyPassword('john', 'wrongpass'));
        $user1 = jAuth::verifyPassword('john', 'passjohn');
        $this->assertNotFalse($user1);
        $userCheck="<object>
                <string property=\"login\">john</string>
                <string property=\"email\">john@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>";
        $this->assertComplexIdenticalStr($user1, $userCheck);

        $user1 = jAuth::verifyPassword('jane', 'passjane');
        $this->assertNotFalse($user1);
        $userCheck="<object>
                <string property=\"login\">jane</string>
                <string property=\"email\">jane@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>";
        $this->assertComplexIdenticalStr($user1, $userCheck);

        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('john') as $group) {
            $groups[] = $group;
        }
        $groupCheck="
            <array>
                <object>
                    <string property=\"id_aclgrp\">group1</string>
                </object>
                <object>
                    <string property=\"id_aclgrp\">group2</string>
                </object>
            </array>";
        $this->assertComplexIdenticalStr($groups, $groupCheck);
        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('jane') as $group) {
            $groups[] = $group;
        }
        $groupCheck="
            <array>
                <object>
                    <string property=\"id_aclgrp\">group1</string>
                </object>
            </array>";
        $this->assertComplexIdenticalStr($groups, $groupCheck);
    }

    public function testEmptyPassword() {
        $user1 = jAuth::verifyPassword('john', '');
        $this->assertFalse($user1);
    }

    public function testUsersList() {
        $records = jAuth::getUserList();
        $myUsersLDAP = array();
        foreach($records as $rec) {
            $myUsersLDAP[] = $rec;
        }

        $this->assertEquals(3, count($myUsersLDAP));
        $users="<array>
            <object>
                <string property=\"login\">admin</string>
                <string property=\"email\">admin@localhost</string>
                <string property=\"password\" value=\"d033e22ae348aeb5660fc2140aec35850c4da997\" />
            </object>
            <object>
                <string property=\"login\">john</string>
                <string property=\"email\">john@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>
            <object>
                <string property=\"login\">jane</string>
                <string property=\"email\">jane@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>
        </array>";

        $this->assertComplexIdenticalStr($myUsersLDAP, $users);
    }

    public function testGetUser() {
        $user1 = jAuth::getUser('john');
        $this->assertNotFalse($user1);
        $userCheck="<object>
                <string property=\"login\">john</string>
                <string property=\"email\">john@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>";
        $this->assertComplexIdenticalStr($user1, $userCheck);
    }

    public function testUpdateUser() {
        $myUserLDAP = jAuth::getUser("john");
        $myUserLDAP->email = "test2@jelix.org";
        jAuth::updateUser($myUserLDAP);

        $myUserLDAP = jAuth::getUser("john");
        $userCheck="<object>
                <string property=\"login\">john</string>
                <string property=\"email\">test2@jelix.org</string>
                <string property=\"password\" value=\"!!ldapdao password!!\" />
            </object>";
        $this->assertComplexIdenticalStr($myUserLDAP, $userCheck);
    }

    public function testDeleteUser() {
        $this->assertTrue(jAuth::removeUser("john"));
        $this->assertTrue(jAuth::removeUser("jane"));
        $records = jAuth::getUserList();
        $myUsersLDAP = array();
        foreach ($records as $rec) {
            $myUsersLDAP[] = $rec;
        }
        $this->assertEquals(1, count($myUsersLDAP));
        $this->assertEquals('admin', $myUsersLDAP[0]->login);
        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('john') as $group) {
            $groups[] = $group;
        }
        $this->assertEquals(array(), $groups);
        $groups = array();
        foreach(jAcl2DbUserGroup::getGroupList('jane') as $group) {
            $groups[] = $group;
        }
        $this->assertEquals(array(), $groups);


    }

}
