<?php
/**
 * @package     jelix
 * @subpackage  core_url
 *
 * @author      Laurent Jouanneau
 * @contributor Thibault Piront (nuKs)
 * @contributor Loic Mathaud
 * @contributor Hadrien Lanneau
 *
 * @copyright   2005-2020 Laurent Jouanneau
 * @copyright   2007 Thibault Piront
 * @copyright   2006 Loic Mathaud, 2010 Hadrien Lanneau
 * @copyright   2001-2005 CopixTeam
 * Original code comes from an experimental branch of the Copix Framework (CopixUrl.class.php, Copix 2.3dev20050901, http://www.copix.org),
 * Initial authors of the original code are Gerald Croes and Laurent Jouanneau,
 *
 * @see        http://jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * Object that contains url data, and which provides static method helpers.
 *
 * @package  jelix
 * @subpackage core_url
 *
 * @author      Laurent Jouanneau (for the original code from Copix and enhancement for jelix)
 * @author      Gerald Croes (for the original code from Copix)
 * @contributor Loic Mathaud
 * @contributor Thibault Piront (nuKs)
 */
class jUrl extends jUrlBase
{
    /*#@+
    * constant for get() method
    * @var integer
    */
    const STRING = 0;
    const XMLSTRING = 1;
    const JURL = 2;
    const JURLACTION = 3;
    // #@-

    /**
     * script name including its path.
     *
     * @var string
     */
    public $scriptName;

    /**
     * path info part of the url.
     *
     * @var string
     */
    public $pathInfo = '';

    /**
     * constructor.
     *
     * @param string $scriptname script name
     * @param array  $params     parameters
     * @param string $pathInfo   path info contents
     */
    public function __construct($scriptname = '', $params = array(), $pathInfo = '')
    {
        $this->params = $params;
        $this->scriptName = $scriptname;
        $this->pathInfo = $pathInfo;
    }

    /**
     * converts the url to a string.
     *
     * @param bool $forxml true: some characters will be escaped
     *
     * @return string
     */
    public function toString($forxml = false)
    {
        return $this->getPath().$this->getQuery($forxml);
    }

    /**
     * get the path part of the url (scriptName + pathinfo).
     *
     * @return string
     *
     * @since 1.0.4
     */
    public function getPath()
    {
        $url = $this->scriptName;
        if (substr($this->scriptName, -1) == '/') {
            $url .= ltrim($this->pathInfo, '/');
        } else {
            $url .= $this->pathInfo;
        }

        return $url;
    }

    /**
     * get the query part of the url.
     *
     * @param bool $forxml true: some characters will be escaped
     *
     * @return string
     *
     * @since 1.0.4
     */
    public function getQuery($forxml = false)
    {
        if (count($this->params) > 0) {
            $q = http_build_query($this->params, '', ($forxml ? '&amp;' : '&'));
            if (!$q) {
                return '';
            }
            if (strpos($q, '%3A') !== false) {
                $q = str_replace('%3A', ':', $q);
            }

            return '?'.$q;
        }

        return '';
    }

    //============================== static helper methods

    /**
     * returns the current Url.
     *
     * The URL is the URL for the frontend HTTP server, if your app is behind a proxy.
     *
     * @param bool  $forxml if true, escape some characters to include the url into an html/xml document
     * @param mixed $full
     *
     * @return string the url
     */
    public static function getCurrentUrl($forxml = false, $full = false)
    {
        // we don't take $_SERVER["REQUEST_URI"] because it doesn't correspond to the real URI
        // if the app is behind a proxy with a different basePath than the frontend
        $req = jApp::coord()->request;
        $sel = $req->module.'~'.$req->action;
        if ($full) {
            $url = self::getFull($sel, $req->params, ($forxml ? self::XMLSTRING : self::STRING));
        } else {
            $url = self::get($sel, $req->params, ($forxml ? self::XMLSTRING : self::STRING));
        }

        return $url;
    }

    /**
     * Adds parameters to the given url.
     *
     * @param string $url    an URL
     * @param array  $params some parameters to append to the url
     * @param bool   $forxml if true, escape some characters to include the url into an html/xml document
     *
     * @return string the url
     */
    public static function appendToUrlString($url, $params = array(), $forxml = false)
    {
        $q = http_build_query($params, '', ($forxml ? '&amp;' : '&'));
        if (strpos($q, '%3A') !== false) {
            $q = str_replace('%3A', ':', $q);
        }
        if ((($pos = strpos($url, '?')) !== false) && ($pos !== (strlen($url) - 1))) {
            return $url.($forxml ? '&amp;' : '&').$q;
        }

        return $url.'?'.$q;
    }

    /**
     * Gets the url corresponding to an action, in the given format.
     *
     * @param string $actSel action selector. You can use # instead of the module
     *                       or the action name, to specify the current url.
     * @param array  $params associative array with the parameters
     * @param int    $what   the format you want : one of the jUrl const,
     *                       STRING XMLSTRING JURL JURLACTION
     *
     * @return mixed a value, depending of the $what parameter
     */
    public static function get($actSel, $params = array(), $what = 0)
    {
        $sel = new jSelectorAct($actSel, true, true);
        $params['module'] = $sel->module;
        $params['action'] = $sel->resource;
        $ua = new jUrlAction($params, $sel->request);

        if ($what == 3) {
            return $ua;
        }

        $url = jApp::coord()->geturlActionMapper()->create($ua);

        if ($what == 2) {
            return $url;
        }

        return $url->toString($what != 0);
    }

    /**
     * Gets the absolute url corresponding to an action, in the given format with
     * the domainName in defaultConfig or current.
     *
     * @param string $actSel     action selector. You can use # instead of the module
     *                           or the action name, to specify the current url.
     * @param array  $params     associative array with the parameters
     * @param int    $what       the format you want : only jUrl::STRING or jUrl::XMLSTRING
     * @param string $domainName Customized domain name
     *
     * @throws jException
     *
     * @return string the url string
     */
    public static function getFull($actSel, $params = array(), $what = 0, $domainName = null)
    {
        $domain = '';
        $url = self::get($actSel, $params, ($what != self::XMLSTRING ? self::STRING : $what));
        if (!preg_match('/^http/', $url)) {
            if ($domainName) {
                $domain = $domainName;
                if (!preg_match('/^http/', $domainName)) {
                    $domain = jServer::getProtocol().$domain;
                }
            } else {
                $domain = jServer::getServerURI();
            }

            if ($domain == '') {
                throw new jException('jelix~errors.urls.domain.void');
            }
        } elseif ($domainName != '') {
            $url = str_replace(jServer::getDomainName(), $domainName, $url);
        }

        return $domain.$url;
    }

    /**
     * Parse a url.
     *
     * @param string $scriptNamePath /path/index.php
     * @param string $pathinfo       the path info of the url
     * @param array  $params         url parameter ($_REQUEST)
     *
     * @return jUrlAction
     */
    public static function parse($scriptNamePath, $pathinfo, $params)
    {
        return jApp::coord()->geturlActionMapper()->parse($scriptNamePath, $pathinfo, $params);
    }

    /**
     * escape and simplier a string to be a part of an url path
     * remove or replace not allowed characters etc..
     *
     * @param string $str       the string to escape
     * @param bool   $highlevel false : just to a urlencode. true, replace some characters
     *
     * @return string escaped string
     */
    public static function escape($str, $highlevel = false)
    {
        static $url_escape_from = null;
        static $url_escape_to = null;

        if ($highlevel) {
            if ($url_escape_from == null) {
                $url_escape_from = explode(' ', jLocale::get('jelix~format.url_escape_from'));
                $url_escape_to = explode(' ', jLocale::get('jelix~format.url_escape_to'));
            }
            // first, we do transliteration.
            // we don't use iconv because it is system dependant
            // we don't use strtr because it is not utf8 compliant
            $str = str_replace($url_escape_from, $url_escape_to, $str);
            // then we replace all non word characters by a space
            $str = preg_replace('/([^\\w])/', ' ', $str);
            // then we remove words of 2 letters
            //$str=preg_replace("/(?<=\s)\w{1,2}(?=\s)/"," ",$str);
            // then we replace all spaces by a -
            $str = preg_replace('/( +)/', '-', trim($str));
            // we convert all character to lower case
            $str = urlencode(strtolower($str));

            return $str;
        }

        return urlencode(str_replace(array('-', ' '), array('--', '-'), $str));
    }

    /**
     * perform the opposit of escape.
     *
     * @param string $str the string to escape
     *
     * @return string
     */
    public static function unescape($str)
    {
        return strtr($str, array('--' => '-', '-' => ' '));
    }

    /**
     * @deprecated  FIXME, TO REMOVE
     * return the current url engine
     *
     * @param bool $reset
     *
     * @throws jException
     *
     * @return \Jelix\Routing\UrlMapping\UrlActionMapper
     *
     * @internal call with true parameter, to force to re-instancy the engine. useful for test suite
     */
    public static function getEngine($reset = false)
    {
        if ($reset) {
            $mapperConfig = new \Jelix\Routing\UrlMapping\MapperConfig(jApp::config()->urlengine);
            $urlActionMapper = new \Jelix\Routing\UrlMapping\UrlActionMapper($mapperConfig);
            jApp::coord()->setUrlActionMapper($urlActionMapper);
        }

        return jApp::coord()->getUrlActionMapper();
    }

    /**
     * get the root url for a given ressource type. Root URLs are stored in config file.
     *
     * @param string $ressourceType Name of the ressource
     *
     * @return string the root URL corresponding to this ressource, or basePath if unknown
     */
    public static function getRootUrl($ressourceType)
    {
        $rootUrl = jUrl::getRootUrlRessourceValue($ressourceType);
        if ($rootUrl !== null) {
            if (substr($rootUrl, 0, 7) !== 'http://' && substr($rootUrl, 0, 8) !== 'https://' // url is not absolute.
                && substr($rootUrl, 0, 1) !== '/') { //and is not relative to root
                   // so let's prepend basePath :
                    $rootUrl = jApp::urlBasePath().$rootUrl;
            }
        } else {
            // basePath by default :
            $rootUrl = jApp::urlBasePath();
        }

        return $rootUrl;
    }

    /**
     * get the config value of an item in [rootUrls] section of config.
     *
     * @param string $ressourceType Name of the ressource
     *
     * @return string the config value of this value, null if it does not exist
     */
    public static function getRootUrlRessourceValue($ressourceType)
    {
        if (!isset(jApp::config()->rootUrls[$ressourceType])) {
            return null;
        }

        return jApp::config()->rootUrls[$ressourceType];
    }

    /**
     * tells if the given url is for the current application or if it matches
     * given authorized domains.
     *
     * @param string   $url
     * @param string[] $authorizedDomains
     *
     * @return bool
     */
    public static function isUrlFromApp($url, $authorizedDomains = array())
    {
        $res = @parse_url($url);
        if (!$res) {
            return false;
        }
        if (isset($res['host']) && $res['host'] != '') {
            if ($res['host'] != jServer::getDomainName()) {
                if (!count($authorizedDomains)) {
                    return false;
                }
                if (!in_array($res['host'], $authorizedDomains)) {
                    return false;
                }

                return true;
            }
        }
        $basePath = jApp::urlBasePath();
        $path = (isset($res['path']) && $res['path'] != '' ? $res['path'] : '/');
        $path = rtrim($path, '/').'/';

        return strpos($path, $basePath) === 0;
    }
}
