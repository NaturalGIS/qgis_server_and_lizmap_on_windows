<?php
/**
 * @package    jelix
 * @subpackage auth
 *
 * @author     Laurent Jouanneau
 * @contributor Frédéric Guillot, Antoine Detante, Julien Issler, Dominique Papin, Tahina Ramaroson, Sylvain de Vathaire, Vincent Viaud
 *
 * @copyright  2001-2005 CopixTeam, 2005-2020 Laurent Jouanneau, 2007 Frédéric Guillot, 2007 Antoine Detante
 * @copyright  2007-2008 Julien Issler, 2008 Dominique Papin, 2010 NEOV, 2010 BP2I
 *
 * This classes were get originally from an experimental branch of the Copix project (Copix 2.3dev, http://www.copix.org)
 * Few lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
 * Initial author of this Copix classes is Laurent Jouanneau, and this classes were adapted for Jelix by him
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
require JELIX_LIB_PATH.'auth/jIAuthDriver.iface.php';

require JELIX_LIB_PATH.'auth/jIAuthDriver2.iface.php';

require JELIX_LIB_PATH.'auth/jIAuthDriver3.iface.php';

require JELIX_LIB_PATH.'auth/jAuthDriverBase.class.php';

/**
 * This is the main class for authentification process.
 *
 * @package    jelix
 * @subpackage auth
 */
class jAuth
{
    /**
     * @deprecated
     * @see jAuth::loadConfig()
     */
    protected static function _getConfig()
    {
        return self::loadConfig();
    }

    protected static $config;

    /**
     * @var jIAuthDriver
     */
    protected static $driver;

    /**
     * Load the configuration of authentification, stored in the auth plugin config.
     *
     * The configuration is readed from the auth.coord.ini.php if it exists
     * and indicated into the `coordplugin` section.
     * or readed from the section `auth` from the main configuration
     * or readed from the section `coordplugin_auth` from the main configuration
     *
     * The plugin configuration file can be merged with the section `auth`
     * or `coordplugin_auth` if there is a `auth.mergeconfig`
     * parameter in the `coordplugins` section.
     *
     * The driver to should be indicated into the `driver` configuration
     * parameter from the auth.coord.ini.php file, or into the `driver` configuration
     * of the `coordplugin_auth` section.
     *
     *
     * @param array|null $newconfig a specific configuration of jAuth. If not given,
     *                              configuration is readed from the files
     *
     * @throws jException
     *
     * @return array
     *
     * @since 1.2.10
     */
    public static function loadConfig($newconfig = null)
    {
        if (self::$config === null || $newconfig) {
            if (!$newconfig) {
                if (jApp::coord()) {
                    $plugin = jApp::coord()->getPlugin('auth');
                    if ($plugin === null) {
                        throw new jException('jelix~auth.error.plugin.missing');
                    }
                    $config = &$plugin->config;
                } else {
                    $config = jCoordinator::getPluginConf('auth');
                }
            } else {
                $config = $newconfig;
            }

            // we allow to indicate the driver into the localconfig.ini or mainconfig.ini
            if (isset(jApp::config()->coordplugin_auth, jApp::config()->coordplugin_auth['driver'])) {
                $config['driver'] = trim(jApp::config()->coordplugin_auth['driver']);
            }

            if (!isset($config['session_name'])
                || $config['session_name'] == '') {
                $config['session_name'] = 'JELIX_USER';
            }

            if (!isset($config['persistant_cookie_path'])
                || $config['persistant_cookie_path'] == '') {
                if (jApp::config()) {
                    $config['persistant_cookie_path'] = jApp::urlBasePath();
                } else {
                    $config['persistant_cookie_path'] = '/';
                }
            }

            if (!isset($config['persistant_encryption_key']) || $config['persistant_encryption_key'] == '') {
                // in the case of the use of a separate file, persistant_encryption_key may be into the liveconfig.ini.php
                if (isset(jApp::config()->coordplugin_auth, jApp::config()->coordplugin_auth['persistant_encryption_key'])) {
                    $config['persistant_encryption_key'] = trim(jApp::config()->coordplugin_auth['persistant_encryption_key']);
                } else {
                    $config['persistant_encryption_key'] = '';
                }
            }

            if (!isset($config['persistant_cookie_name'])) {
                $config['persistant_cookie_name'] = 'jauthSession';
            }

            // Read hash method configuration. If not empty, cryptPassword will use
            // the new API of PHP 5.5 (password_verify and so on...)
            $password_hash_method = (isset($config['password_hash_method']) ? $config['password_hash_method'] : 0);

            if ($password_hash_method === '' || (!is_numeric($password_hash_method))) {
                $password_hash_method = 0;
            } else {
                $password_hash_method = intval($password_hash_method);
            }

            $password_hash_options = (isset($config['password_hash_options']) ? $config['password_hash_options'] : '');
            if ($password_hash_options != '') {
                $list = '{"'.str_replace(array('=', ';'), array('":"', '","'), $config['password_hash_options']).'"}';
                $password_hash_options = @json_decode($list, true);
                if (!$password_hash_options) {
                    $password_hash_options = array();
                }
            } else {
                $password_hash_options = array();
            }

            $config['password_hash_method'] = $password_hash_method;
            $config['password_hash_options'] = $password_hash_options;

            $config[$config['driver']] = self::_buildDriverConfig($config, jApp::config());

            if (isset($config['url_return_external_allowed_domains']) && $config['url_return_external_allowed_domains']) {
                if (is_string($config['url_return_external_allowed_domains'])) {
                    $config['url_return_external_allowed_domains'] = array($config['url_return_external_allowed_domains']);
                }
            } else {
                $config['url_return_external_allowed_domains'] = array();
            }

            self::$config = $config;
            self::$driver = null;
        }

        return self::$config;
    }

    /**
     * @deprecated
     * @see jAuth::getDriver()
     */
    protected static function _getDriver()
    {
        return self::getDriver();
    }

    /**
     * read the configuration specific to the authentication driver
     *
     * the driver config is readed from the section named after the driver
     * name, into $authconfig. And into the `auth_<drivername>` from the
     * main configuration. Both are merged if they exist both.
     *
     * @param array $authConfig content of the auth.coord.ini.php or the `auth`
     *                          section or the `coordplugin_auth` section;
     * @param object $appConfig
     * @return array|null
     */
    protected static function _buildDriverConfig($authConfig, $appConfig)
    {
        $driver = $authConfig['driver'];
        $driverConfig = array();
        if (isset($authConfig[$driver]) && is_array($authConfig[$driver])) {
            $driverConfig = $authConfig[$driver];
        }

        $section = 'auth_'.strtolower($driver);
        if (isset($appConfig->$section) && is_array($appConfig->$section)) {
            $driverConfig = array_merge($driverConfig, $appConfig->$section);
        }

        if (!count($driverConfig)) {
            return null;
        }

        // put the global password_hash_* values into the driver config
        // so the driver access to it easily
        $driverConfig['password_hash_method'] = $authConfig['password_hash_method'];
        $driverConfig['password_hash_options'] = $authConfig['password_hash_options'];

        return $driverConfig;
    }

    /**
     * @throws jException
     *
     * @return mixed
     */
    public static function getDriverConfig()
    {
        $authConfig = self::loadConfig();
        $driver = $authConfig['driver'];

        return $authConfig[$driver];
    }

    /**
     * return the auth driver.
     *
     * @throws jException
     *
     * @return jIAuthDriver
     *
     * @since 1.2.10
     */
    public static function getDriver()
    {
        if (self::$driver === null) {
            $config = self::loadConfig();
            $driverConfig = self::getDriverConfig();
            $driverName = strtolower($config['driver']);
            $driver = jApp::loadPlugin(
                $driverName,
                'auth',
                '.auth.php',
                $driverName.'AuthDriver',
                $driverConfig
            );
            if (is_null($driver)) {
                throw new jException('jelix~auth.error.driver.notfound', $driverName);
            }
            self::$driver = $driver;
        }

        return self::$driver;
    }

    /**
     * return the value of a parameter of the configuration of the current driver.
     *
     * @param string $paramName
     *
     * @return string the value. null if it doesn't exist
     */
    public static function getDriverParam($paramName)
    {
        $config = self::getDriverConfig();
        if (isset($config[$paramName])) {
            return $config[$paramName];
        }

        return null;
    }

    /**
     * load user data.
     *
     * This method returns an object, generated by the driver, and which contains
     * data corresponding to the given login. This method should be called if you want
     * to update data of a user. see updateUser method.
     *
     * @param string $login
     *
     * @return object the user
     */
    public static function getUser($login)
    {
        $dr = self::getDriver();

        return $dr->getUser($login);
    }

    /**
     * Create a new user object.
     *
     * You should call this method if you want to create a new user. It returns an object,
     * representing a user. Then you should fill its properties and give it to the saveNewUser
     * method.
     *
     * @param string $login    the user login
     * @param string $password the user password (not encrypted)
     *
     * @return object the returned object depends on the driver
     *
     * @since 1.0b2
     */
    public static function createUserObject($login, $password)
    {
        $dr = self::getDriver();

        return $dr->createUserObject($login, $password);
    }

    /**
     * Save a new user.
     *
     * if the saving has succeed, a AuthNewUser event is sent
     * The given object should have been created by calling createUserObject method :
     *
     * example :
     *  <pre>
     *   $user = jAuth::createUserObject('login','password');
     *   $user->email ='bla@foo.com';
     *   jAuth::saveNewUser($user);
     *  </pre>
     *  the type of $user depends of the driver, so it can have other properties.
     *
     * @param object $user the user data
     *
     * @return object the user (eventually, with additional data)
     */
    public static function saveNewUser($user)
    {
        $dr = self::getDriver();
        if ($dr->saveNewUser($user)) {
            $eventResp = jEvent::notify('AuthNewUser', array('user' => $user));
            $allResponses = array();
            if ($eventResp->inResponse('doUpdate', true, $allResponses)) {
                $dr->updateUser($user);
            }
        }

        return $user;
    }

    /**
     * update user data.
     *
     * It send a AuthUpdateUser event if the saving has succeed. If you want
     * to change the user password, you must use jAuth::changePassword method
     * instead of jAuth::updateUser method.
     *
     * The given object should have been created by calling getUser method.
     * Example :
     *  <pre>
     *   $user = jAuth::getUser('login');
     *   $user->email ='bla@foo.com';
     *   jAuth::updateUser($user);
     *  </pre>
     *  the type of $user depends of the driver, so it can have other properties.
     *
     * @param object $user user data
     *
     * @return bool true if the user has been updated
     */
    public static function updateUser($user)
    {
        $dr = self::getDriver();
        if ($dr->updateUser($user) === false) {
            return false;
        }

        if (self::isConnected() && self::getUserSession()->login === $user->login) {
            $config = self::loadConfig();
            $_SESSION[$config['session_name']] = $user;
        }
        jEvent::notify('AuthUpdateUser', array('user' => $user));

        return true;
    }

    /**
     * remove a user
     * send first AuthCanRemoveUser event, then if ok, send AuthRemoveUser
     * and then remove the user.
     *
     * @param string $login the user login
     *
     * @return bool true if ok
     */
    public static function removeUser($login)
    {
        $dr = self::getDriver();
        $eventresp = jEvent::notify('AuthCanRemoveUser', array('login' => $login));
        foreach ($eventresp->getResponse() as $rep) {
            if (!isset($rep['canremove']) || $rep['canremove'] === false) {
                return false;
            }
        }
        $user = $dr->getUser($login);
        if ($dr->removeUser($login) === false) {
            return false;
        }
        jEvent::notify('AuthRemoveUser', array('login' => $login, 'user' => $user));
        if (self::isConnected() && self::getUserSession()->login === $login) {
            self::logout();
        }

        return true;
    }

    /**
     * construct the user list.
     *
     * @param string $pattern '' for all users
     *
     * @return object[] array of objects representing the users
     */
    public static function getUserList($pattern = '%')
    {
        $dr = self::getDriver();

        return $dr->getUserlist($pattern);
    }

    /**
     * Indicate if the password can be changed technically.
     *
     * Not related to rights with jAcl2
     *
     * @param string $login the login of the user
     *
     * @return bool
     *
     * @since 1.6.21
     */
    public static function canChangePassword($login)
    {
        $dr = self::getDriver();
        if ($dr instanceof jIAuthDriver2) {
            return $dr->canChangePassword($login);
        }

        return true;
    }

    /**
     * If the password cannot be changed, this method gives the reason.
     *
     * It may returns a reason only after a call of the canChangePassword()
     * method.
     *
     * @return string
     * @throws jException
     * @since 1.6.37
     */
    public static function getReasonToForbiddenPasswordChange()
    {
        $dr = self::getDriver();
        if ($dr instanceof jIAuthDriver3) {
            return $dr->getReasonToForbiddenPasswordChange();
        }
        return '';
    }

    /**
     * change a user password
     *
     * @param string $login       the login of the user
     * @param string $newpassword the new password (not encrypted)
     *
     * @return bool true if the change succeed
     */
    public static function changePassword($login, $newpassword)
    {
        $dr = self::getDriver();
        if ($dr->changePassword($login, $newpassword) === false) {
            return false;
        }
        jEvent::notify('AuthChangePassword', array('login' => $login, 'password' => $newpassword));
        if (self::isConnected() && self::getUserSession()->login === $login) {
            $config = self::loadConfig();
            $_SESSION[$config['session_name']] = self::getUser($login);
        }

        return true;
    }

    /**
     * verify that the password correspond to the login.
     *
     * @param string $login    the login of the user
     * @param string $password the password to test (not encrypted)
     *
     * @return false|object if ok, returns the user as object
     */
    public static function verifyPassword($login, $password)
    {
        $dr = self::getDriver();

        return $dr->verifyPassword($login, $password);
    }

    /**
     * authentificate a user, and create a user in the php session.
     *
     * @param string $login      the login of the user
     * @param string $password   the password to test (not encrypted)
     * @param bool   $persistant (optional) the session must be persistant
     *
     * @return bool true if authentification is ok
     * @jelixevent AuthBeforeLogin  listeners should return processlogin=false to
     *                          refuse authentication and to avoid a password check
     *                          (when a user is blacklisted for exemple)
     *                          you can also respond to this event to do record
     *                          in a log file or else.
     *                          parameters: login
     * @jelixevent AuthCanLogin  sent when password is ok.
     *                          parameters: login, user=user object
     *                          listeners can respond with canlogin=false to refuse the authentication.
     * @jelixevent AuthLogin     sent when the login process is finished and the user
     *                           is authenticated. listeners receive the login
     *                           and a boolean indicating the persistence
     * @jelixevent AuthErrorLogin sent when the password is bad. Listeners receive
     *                           the login.
     */
    public static function login($login, $password, $persistant = false)
    {
        $dr = self::getDriver();
        $config = self::loadConfig();

        $eventresp = jEvent::notify('AuthBeforeLogin', array('login' => $login));
        foreach ($eventresp->getResponse() as $rep) {
            if (isset($rep['processlogin']) && $rep['processlogin'] === false) {
                return false;
            }
        }

        if ($user = $dr->verifyPassword($login, $password)) {
            // the given login may be another property like email, so get the real login
            $login = $user->login;
            $eventresp = jEvent::notify('AuthCanLogin', array('login' => $login, 'user' => $user));
            foreach ($eventresp->getResponse() as $rep) {
                if (!isset($rep['canlogin']) || $rep['canlogin'] === false) {
                    return false;
                }
            }

            $_SESSION[$config['session_name']] = $user;

            if ($persistant) {
                $persistence = self::generateCookieToken($login, $password);
            } else {
                $persistence = 0;
            }

            jEvent::notify('AuthLogin', array('login' => $login, 'persistence' => $persistence));

            return true;
        }
        jEvent::notify('AuthErrorLogin', array('login' => $login));

        return false;
    }

    /**
     * Check if persistant session is enabled in config.
     *
     * @return bool true if persistant session in enabled
     */
    public static function isPersistant()
    {
        $config = self::loadConfig();
        if (!isset($config['persistant_enable'])) {
            return false;
        }

        return $config['persistant_enable'];
    }

    /**
     * logout a user and delete the user in the php session.
     *
     * @jelixevent AuthLogout listeners received the login
     */
    public static function logout()
    {
        $config = self::loadConfig();
        jEvent::notify('AuthLogout', array('login' => $_SESSION[$config['session_name']]->login));
        $_SESSION[$config['session_name']] = new jAuthDummyUser();

        if (isset($config['session_destroy']) && $config['session_destroy']) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            session_destroy();
        }

        if (isset($config['persistant_enable']) && $config['persistant_enable']) {
            if (isset($config['persistant_cookie_name'])) {
                setcookie($config['persistant_cookie_name'], '', time() - 3600, $config['persistant_cookie_path'], '', false, true);
            } else {
                jLog::log(jLocale::get('jelix~auth.error.persistant.incorrectconfig', 'persistant_cookie_name'), 'error');
            }
        }
    }

    /**
     * Says if the user is connected.
     *
     * @return bool
     */
    public static function isConnected()
    {
        $config = self::loadConfig();

        return isset($_SESSION[$config['session_name']]) && $_SESSION[$config['session_name']]->login != '';
    }

    /**
     * return the user stored in the php session.
     *
     * @return object the user data
     */
    public static function getUserSession()
    {
        $config = self::loadConfig();
        if (!isset($_SESSION[$config['session_name']])
            || !$_SESSION[$config['session_name']]) {
            $_SESSION[$config['session_name']] = new jAuthDummyUser();
        }

        return $_SESSION[$config['session_name']];
    }

    /**
     * @deprecated
     * @see reloadUserSession()
     */
    public static function reloadUser()
    {
        return self::reloadUserSession();
    }

    public static function reloadUserSession()
    {
        $dr = self::getDriver();
        $config = self::loadConfig();
        $user = null;
        if ((isset($_SESSION[$config['session_name']]) && $_SESSION[$config['session_name']]->login != '')) {
            $user = $dr->getUser($_SESSION[$config['session_name']]->login);
        }
        if (!$user) {
            $user = new jAuthDummyUser();
        }
        $_SESSION[$config['session_name']] = $user;

        return $user;
    }

    /**
     * Sets the given user in session without authentication.
     *
     * It is useful if you manage a kind of session that is not the PHP session.
     * For example, in a controller, you call jAuth::login() to verify the
     * authentication, (and allowing listeners to interact during the authentication).
     * In other controller, you just call setUserSession() with the login
     * you retrieve some where, with the help of some request parameters (from
     * a JWT token for example).
     * And you could call jAuth::logout() when the user ends its "session".
     *
     * @param string $login
     *
     * @return object the user data
     *
     * @since 1.6.30
     */
    public static function setUserSession($login)
    {
        $config = self::loadConfig();
        $user = self::getUser($login);
        if ($user) {
            $_SESSION[$config['session_name']] = $user;
        }

        return $user;
    }

    /**
     * generate a password with random letters, numbers and special characters.
     *
     * @param int  $length              the length of the generated password
     * @param bool $withoutSpecialChars (optional, default false) the generated password may be use this characters : !@#$%^&*?_,~
     *
     * @return string the generated password
     */
    public static function getRandomPassword($length = 12, $withoutSpecialChars = false)
    {
        return jAuthPassword::getRandomPassword($length, $withoutSpecialChars);
    }

    /**
     * check the token from the cookie used for persistant session.
     *
     * If the cookie is good, the login  is made
     *
     * @throws jException
     *
     * @return bool true if the cookie was ok and login has been succeed
     */
    public static function checkCookieToken()
    {
        $config = self::loadConfig();
        if (isset($config['persistant_enable']) && $config['persistant_enable'] && !self::isConnected()) {
            if (trim($config['persistant_cookie_name']) != ''
                && trim($config['persistant_encryption_key']) != ''
                ) {
                $cookieName = $config['persistant_cookie_name'];
                if (isset($_COOKIE[$cookieName])
                    && is_string($_COOKIE[$cookieName])
                    && strlen($_COOKIE[$cookieName])) {
                    try {
                        $cryptokey = \Defuse\Crypto\Key::loadFromAsciiSafeString(
                            $config['persistant_encryption_key']
                        );
                        $decrypted = \Defuse\Crypto\Crypto::decrypt(
                            $_COOKIE[$cookieName],
                            $cryptokey
                        );
                    } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {
                        jLog::log('User not logged with authentication cookie: error during decryption of the cookie. Cookie not encrypted with the current encryption key.', 'notice');

                        return false;
                    } catch (\Defuse\Crypto\Exception\CryptoException $e) {
                        jLog::log('User not logged with authentication cookie: error during decryption of the cookie. Bad encryption key or bad cookie content.', 'warning');

                        return false;
                    }
                    $decrypted = json_decode($decrypted, true);
                    if ($decrypted && is_array($decrypted) && count($decrypted) == 2) {
                        list($login, $password) = $decrypted;

                        return self::login($login, $password, true);
                    }
                }
            }
        }

        return false;
    }

    /**
     * Generate and set an encrypted cookie with the given login password.
     *
     * The cookie may not be set if the persistence is disable or if there is
     * an issue with the encryption.
     *
     * @param string $login
     * @param string $password
     *
     * @return int expiration date (UNIX timestamp), or 0 if cookie is not set
     */
    public static function generateCookieToken($login, $password)
    {
        $persistence = 0;
        $config = self::loadConfig();

        // Add a cookie for session persistance, if enabled
        if (isset($config['persistant_enable']) && $config['persistant_enable']) {
            if (trim($config['persistant_encryption_key']) == ''
                || trim($config['persistant_cookie_name']) == '') {
                jLog::log(jLocale::get('jelix~auth.error.persistant.incorrectconfig', 'persistant_cookie_name, persistant_encryption_key'), 'error');

                return 0;
            }

            if (isset($config['persistant_duration'])) {
                $persistence = intval($config['persistant_duration']) * 86400;
            } else {
                $persistence = 86400; // 24h
            }
            $persistence += time();

            try {
                $cryptokey = \Defuse\Crypto\Key::loadFromAsciiSafeString($config['persistant_encryption_key']);
                $encrypted = \Defuse\Crypto\Crypto::encrypt(json_encode(array($login, $password)), $cryptokey);
                setcookie($config['persistant_cookie_name'], $encrypted, $persistence, $config['persistant_cookie_path'], '', false, true);
            } catch (\Defuse\Crypto\Exception\CryptoException $e) {
                jLog::log('Cookie for persistant authentication. Error during encryption of the cookie token for authentication: '.$e->getMessage(), 'warning');

                return false;
            }
        }

        return $persistence;
    }

    public static function checkReturnUrl($url)
    {
        if ($url == '') {
            return false;
        }
        $config = self::loadConfig();

        return jUrl::isUrlFromApp($url, $config['url_return_external_allowed_domains']);
    }
}
