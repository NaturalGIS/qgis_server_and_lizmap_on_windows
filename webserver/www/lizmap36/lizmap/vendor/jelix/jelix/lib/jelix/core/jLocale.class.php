<?php
/**
 * @package    jelix
 * @subpackage core
 *
 * @author     Laurent Jouanneau
 * @author     Gerald Croes
 * @copyright  2001-2005 CopixTeam, 2005-2016 Laurent Jouanneau
 * Some parts of this file are took from Copix Framework v2.3dev20050901, CopixI18N.class.php, http://www.copix.org.
 * copyrighted by CopixTeam and released under GNU Lesser General Public Licence.
 * initial authors : Gerald Croes, Laurent Jouanneau.
 * enhancement by Laurent Jouanneau for Jelix.
 *
 * @see        http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * static class to get a localized string.
 *
 * @package  jelix
 * @subpackage core
 */
class jLocale
{
    /**
     * @var jBundle[][]
     */
    public static $bundles = array();

    /**
     * static class...
     */
    private function __construct()
    {
    }

    /**
     * gets the current locale (xx_YY).
     *
     * @return string
     *
     * @since 1.7.0
     */
    public static function getCurrentLocale()
    {
        return jApp::config()->locale;
    }

    /**
     * gets the current lang (xx from xx_YY).
     *
     * @return string
     */
    public static function getCurrentLang()
    {
        $s = jApp::config()->locale;

        return substr($s, 0, strpos($s, '_'));
    }

    /**
     * gets the current country (YY from xx_YY).
     *
     * @return string
     */
    public static function getCurrentCountry()
    {
        $s = jApp::config()->locale;

        return substr($s, strpos($s, '_') + 1);
    }

    /**
     * gets the correct string, for a given language.
     *   if it can't get the correct language, it will try to gets the string
     *   from the default language.
     *   if both fails, it will raise an exception.
     *
     * @param string $key             the key of the localized string
     * @param array  $args            arguments to apply to the localized string with sprintf
     * @param string $locale          the lang code. if null, use the default language
     * @param string $charset         the charset code. if null, use the default charset
     * @param bool   $tryOtherLocales if true and if the method does not find
     *                                the locale file or the key, it will try with the default
     *                                locale, the fallback local or similar locale
     *
     * @throws Exception
     * @throws jExceptionSelector
     *
     * @return string the localized string
     */
    public static function get($key, $args = null, $locale = null, $charset = null, $tryOtherLocales = true)
    {
        $config = jApp::config();

        try {
            $file = new jSelectorLoc($key, $locale, $charset);
        } catch (jExceptionSelector $e) {
            // the file is not found
            if ($e->getCode() == 12) {
                // unknown module..
                throw $e;
            }
            if ($charset === null) {
                $charset = $config->charset;
            }

            throw new Exception('(212)No locale file found for the given locale key "'.$key
                            .'" in any other default languages (charset '.$charset.')');
        }

        $locale = $file->locale;
        $keySelector = $file->module.'~'.$file->fileKey;

        if (!isset(self::$bundles[$keySelector][$locale])) {
            self::$bundles[$keySelector][$locale] = new jBundle($file, $locale);
        }

        $bundle = self::$bundles[$keySelector][$locale];

        //try to get the message from the bundle.
        $string = $bundle->get($file->messageKey, $file->charset);
        if ($string === null) {

            // locale key has not been found
            if (!$tryOtherLocales) {
                throw new Exception('(210)The given locale key "'.$file->toString().
                                    '" does not exists (lang:'.$file->locale.
                                    ', charset:'.$file->charset.')');
            }

            $words = self::tryOtherLocales($key, $args, $locale, $charset, $config);
            if ($words === null) {
                throw new Exception('(213)The given locale key "'.$file->toString().
                                    '" does not exists in any default languages for the '.$file->charset.' charset');
            }

            return $words;
        }

        //here, we know the message
        if ($args !== null && $args !== array()) {
            $string = call_user_func_array('sprintf', array_merge(array($string), is_array($args) ? $args : array($args)));
        }

        return $string;
    }

    /**
     * return the list of alternative locales to the given one.
     *
     * @param string $locale
     * @param object $config the configuration object
     *
     * @return array
     */
    public static function getAlternativeLocales($locale, $config)
    {
        $otherLocales = array();
        $similarLocale = self::langToLocale(substr($locale, 0, strpos($locale, '_')));
        if ($similarLocale != $locale) {
            $otherLocales[] = $similarLocale;
        }

        if ($locale != $config->locale) {
            $otherLocales[] = $config->locale;
        }

        if ($config->fallbackLocale && $locale != $config->fallbackLocale) {
            $otherLocales[] = $config->fallbackLocale;
        }

        return $otherLocales;
    }

    protected static function tryOtherLocales($key, $args, $locale, $charset, $config)
    {
        $otherLocales = self::getAlternativeLocales($locale, $config);
        foreach ($otherLocales as $loc) {
            try {
                return jLocale::get($key, $args, $loc, $charset, false);
            } catch (Exception $e) {
            }
        }

        return null;
    }

    /**
     * says if the given locale or lang code is available in the application.
     *
     * @param string $locale               the locale code (xx_YY) or a lang code (xx)
     * @param bool   $strictCorrespondance if true don't try to find a locale from an other country
     * @param mixed  $l
     *
     * @return string the corresponding locale
     */
    public static function getCorrespondingLocale($l, $strictCorrespondance = false)
    {
        if (strpos($l, '_') === false) {
            $l = self::langToLocale($l);
        }

        if ($l != '') {
            $avLoc = &jApp::config()->availableLocales;
            if (in_array($l, $avLoc)) {
                return $l;
            }
            if ($strictCorrespondance) {
                return '';
            }
            $l2 = self::langToLocale(substr($l, 0, strpos($l, '_')));
            if ($l2 != $l && in_array($l2, $avLoc)) {
                return $l2;
            }
        }

        return '';
    }

    /**
     * returns the locale corresponding of one of the accepted language indicated
     * by the browser, and which is available in the application.
     *
     * @return string the locale. empty if not found.
     */
    public static function getPreferedLocaleFromRequest()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return '';
        }

        $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($languages as $bl) {
            if (!preg_match('/^([a-zA-Z]{2,3})(?:[-_]([a-zA-Z]{2,3}))?(;q=[0-9]\\.[0-9])?$/', $bl, $match)) {
                continue;
            }
            $l = strtolower($match[1]);
            if (isset($match[2])) {
                $l .= '_'.strtoupper($match[2]);
            }
            $lang = self::getCorrespondingLocale($l);
            if ($lang != '') {
                return $lang;
            }
        }

        return '';
    }

    /**
     * @var array content of the lang_to_locale.ini.php
     */
    protected static $langToLocale;

    /**
     * returns the locale corresponding to a lang.
     *
     * The file lang_to_locale gives corresponding locales, but you can override these
     * association into the langToLocale section of the main configuration
     *
     * @param string $lang a lang code (xx)
     *
     * @return string the corresponding locale (xx_YY)
     */
    public static function langToLocale($lang)
    {
        $conf = jApp::config();
        if (isset($conf->langToLocale['locale'][$lang])) {
            return $conf->langToLocale['locale'][$lang];
        }
        if (is_null(self::$langToLocale)) {
            $content = @parse_ini_file(JELIX_LIB_CORE_PATH.'lang_to_locale.ini.php');
            self::$langToLocale = $content['locale'];
        }
        if (isset(self::$langToLocale[$lang])) {
            return self::$langToLocale[$lang];
        }

        return '';
    }

    /**
     * @var string[][] first key is lang code of translation of names, second key is lang code
     */
    protected static $langNames = array();

    /**
     * @param string $lang       the lang for which we want the name
     * @param string $langOfName if empty, return the name in its own language
     *
     * @since 1.7.0
     */
    public static function getLangName($lang, $langOfName = '')
    {
        if ($langOfName == '') {
            $langOfName = '_';
        }

        if (!isset(self::$langNames[$langOfName])) {
            $fileName = 'lang_names_'.$langOfName.'.ini';
            if (!file_exists(__DIR__.'/'.$fileName)) {
                $fileName = 'lang_names_en.ini';
            }
            $names = parse_ini_file($fileName, false, INI_SCANNER_RAW);
            self::$langNames[$langOfName] = $names['names'];
        }

        if (isset(self::$langNames[$langOfName][$lang])) {
            return self::$langNames[$langOfName][$lang];
        }

        return $lang;
    }
}
