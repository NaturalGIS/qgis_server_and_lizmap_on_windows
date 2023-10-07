<?php

/**
 * @author     Loic Mathaud
 * @contributor Laurent Jouanneau
 * @contributor Erika31, Julien Issler
 * @copyright  2006 Loic Mathaud, 2008-2019 Laurent Jouanneau, 2017 Erika31, 2017 Julien Issler
 * @link        http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\IniFile;

/**
 * utility class to read and write an ini file.
 */
class Util
{
    /**
     * read an ini file.
     *
     * @param string $filename the path and the name of the file to read
     * @param bool   $asObject true if the content should be returned as an object
     *
     * @return object|false the content of the file or false if file does not exists
     */
    public static function read($filename, $asObject = false)
    {
        if (file_exists($filename)) {
            if ($asObject) {
                return (object) parse_ini_file($filename, true, INI_SCANNER_TYPED);
            } else {
                return parse_ini_file($filename, true, INI_SCANNER_TYPED);
            }
        } else {
            return false;
        }
    }

    /**
     * Flag for merging. Directive whose name starts with '_' are not
     * modified during a merge.
     */
    const NOT_MERGE_PROTECTED_DIRECTIVE = 1;

    /**
     * Flag for merging. When directive are arrays with numerical keys, like
     * `foo[]=bar` (here the directive foo), if the content to import contains
     * also a directive of the same name, both array are merged. (By default, the
     * array to import replace the original array).
     *
     * It does not change the behavior of the merge of associative arrays (like `foo[mykey]=bar`),
     * for which there is always a normal merge.
     */
    const NORMAL_MERGE_ARRAY_VALUES_WITH_INTEGER_KEYS = 2;

    /**
     * read an ini file and merge its parameters to the given object.
     * Useful to merge to config files.
     * Parameters whose name starts with a '_' are not merged.
     *
     * @param string $filename the path and the name of the file to read
     * @param object $content
     * @param int    $flags    a combination of constants NOT_MERGE_*, NORMAL_MERGE_*
     * @param array  $ignoredSection list of sections or top level parameters that
     *                               should not be merged
     *
     * @return object|false the content of the file or false if error during parsing the file
     *
     * @since 2.0
     */
    public static function readAndMergeObject($filename, $content, $flags = 0,
                                              $ignoredSection = array())
    {
        if (!file_exists($filename)) {
            return false;
        }

        $newContent = @parse_ini_file($filename, true, INI_SCANNER_TYPED);
        if ($newContent === false) {
            return false;
        }

        return self::mergeIniObjectContents($content, $newContent, $flags, $ignoredSection);
    }

    /**
     * merge two simple StdClass object.
     *
     * @param object $baseContent     the object which receives new properties
     * @param object $contentToImport the object providing new properties
     * @param int    $flags           a combination of constants NOT_MERGE_*, NORMAL_MERGE_*
     * @param array  $ignoredSection  list of sections or top level parameters that
     *                                should not be merged
     *
     * @return object $baseContent
     */
    public static function mergeIniObjectContents(
        $baseContent,
        $contentToImport,
        $flags = 0,
        $ignoredSection = array()
    )
    {
        $contentToImport = (array) $contentToImport;

        foreach ($contentToImport as $k => $v) {
            if (!isset($baseContent->$k)) {
                $baseContent->$k = $v;
                continue;
            }

            if (($flags & self::NOT_MERGE_PROTECTED_DIRECTIVE) && $k[0] == '_') {
                continue;
            }

            if (in_array($k, $ignoredSection)) {
                continue;
            }

            if (is_array($v)) {
                // this is a section or a array value
                if (!is_array($baseContent->$k)) {
                    $baseContent->$k = $v;
                } else {
                    if ($flags & self::NORMAL_MERGE_ARRAY_VALUES_WITH_INTEGER_KEYS) {
                        if (self::hasOnlyIntegerKeys($v) && self::hasOnlyIntegerKeys($baseContent->$k)) {
                            $baseContent->$k = array_merge($baseContent->$k, $v);
                            continue;
                        }
                        $newbase = $baseContent->$k;
                    } else {
                        // first, in the case of an array value, clean the $base value, by removing all values with numerical keys
                        // as it does not make sens to merge two simple arrays here.
                        $newbase = array();
                        foreach ($baseContent->$k as $k2 => $v2) {
                            if (is_string($k2)) {
                                $newbase[$k2] = $v2;
                            }
                        }
                    }
                    $baseContent->$k = self::mergeSectionContent($newbase, $v, $flags);
                }
            } else {
                $baseContent->$k = $v;
            }
        }

        return $baseContent;
    }

    protected static function hasOnlyIntegerKeys(&$arr)
    {
        foreach ($arr as $k => $v) {
            if (!is_integer($k)) {
                return false;
            }
        }

        return true;
    }

    protected static function mergeSectionContent($base, $toImport, $flags = 0)
    {
        foreach ($toImport as $k => $v) {
            if (!isset($base[$k])) {
                $base[$k] = $v;
                continue;
            }

            if (($flags & self::NOT_MERGE_PROTECTED_DIRECTIVE) && $k[0] == '_') {
                continue;
            }

            if (is_array($v) && is_array($base[$k])) {
                if ($flags & self::NORMAL_MERGE_ARRAY_VALUES_WITH_INTEGER_KEYS) {
                    $newbase = $base[$k];
                } else {
                    // first, clean the $base value, by removing all values with numerical keys
                    // as it does not make sens to merge two simple arrays here.
                    $newbase = array();
                    foreach ($base[$k] as $k2 => $v2) {
                        if (is_string($k2) && !is_numeric($k2)) {
                            $newbase[$k2] = $v2;
                        }
                    }
                }
                // a section
                $base[$k] = array_merge($newbase, $v);
            } else {
                $base[$k] = $v;
            }
        }

        return $base;
    }

    /**
     * write some data in an ini file
     * the data array should follow the same structure returned by
     * the read method (or parse_ini_file).
     *
     * @param array  $array    the content of an ini file
     * @param string $filename the path and the name of the file use to store the content
     * @param string $header   some content to insert at the begining of the file
     * @param int    $chmod
     */
    public static function write($array, $filename, $header = '', $chmod = null)
    {
        $result = '';
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result .= '['.$k."]\n";
                foreach ($v as $k2 => $v2) {
                    $result .= self::_iniValue($k2, $v2);
                }
            } else {
                // we put simple values at the beginning of the file.
                $result = self::_iniValue($k, $v).$result;
            }
        }

        if ($f = @fopen($filename, 'wb')) {
            fwrite($f, $header.$result);
            fclose($f);
            if ($chmod) {
                chmod($filename, $chmod);
            }
        } else {
            throw new IniException('Error while writing ini file '.$filename, 24);
        }
    }

    /**
     * format a value to store in a ini file.
     *
     * @param string $value the value
     *
     * @return string the formated value
     */
    private static function _iniValue($key, $value)
    {
        if (is_array($value)) {
            $res = '';
            foreach ($value as $v) {
                $res .= self::_iniValue($key.'[]', $v);
            }

            return $res;
        } elseif ($value == ''
                  || is_numeric($value)
                  || (is_string($value) &&
                      preg_match("/^[\w\\-\\.]*$/", $value) &&
                      strpos("\n", $value) === false)
        ) {
            return $key.'='.$value."\n";
        } elseif ($value === false) {
            return $key."=off\n";
        } elseif ($value === true) {
            return $key."=on\n";
        } else {
            return $key.'="'.$value."\"\n";
        }
    }
}
