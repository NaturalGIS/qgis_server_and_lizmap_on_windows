<?php

/**
 * @author     Laurent Jouanneau
 * @copyright  2008-2020 Laurent Jouanneau
 *
 * @link       http://jelix.org
 * @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\ComposerPlugin\Ini;

/**
 * utility class to read an ini file by preserving comments, whitespace..
 * It follows same behaviors of parse_ini_file, except when there are quotes
 * inside values. it doesn't support quotes inside values, because parse_ini_file
 * is totally bugged, depending cases.
 *
 * Use it only with IniModifierArray or MultiIniModifier. If you only want to
 * read ini content, use parse_ini_file, it's better in term of performance.
 */
class IniReader implements IniReaderInterface
{
    /**
     * @const integer token type for whitespaces
     */
    const TK_WS = 0;
    /**
     * @const integer token type for a comment
     */
    const TK_COMMENT = 1;
    /**
     * @const integer token type for a section header
     */
    const TK_SECTION = 2;
    /**
     * @const integer token type for a simple value
     */
    const TK_VALUE = 3;
    /**
     * @const integer token type for a value of an array item
     */
    const TK_ARR_VALUE = 4;

    /**
     * each item of this array contains data for a section. the key of the item
     * is the section name. There is a section with the key "0", and which contains
     * data for options which are not in a section.
     * each value of the items is an array of tokens. A token is an array with
     * some values. first value is the token type (see TK_* constants), and other
     * values depends of the token type:
     * - TK_WS: content of whitespaces
     * - TK_COMMENT: the comment
     * - TK_SECTION: the section name
     * - TK_VALUE: the name, and the value
     * - TK_ARRAY_VALUE: the name, the value, and the key.
     *
     * @var array
     */
    protected $content = array();

    /**
     * @var string the filename of the ini file
     */
    protected $filename = '';

    /**
     * load the given ini file.
     *
     * @param string $filename the file to load
     */
    public function __construct($filename)
    {
        if (!file_exists($filename) || !is_file($filename)) {
            throw new \InvalidArgumentException("The file $filename does not exists");
        }
        $this->filename = $filename;
        $this->parse(preg_split("/(\r\n|\n|\r)/", file_get_contents($filename)));
    }

    public function isEmpty()
    {
        return (count($this->content) == 0);
    }

    /**
     * @return string the file name
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * parsed the lines of the ini file.
     */
    protected function parse($lines)
    {
        $this->content = array(0 => array());
        $currentSection = 0;
        $multiline = false;
        $currentValue = null;

        $arrayContents = array();

        foreach ($lines as $num => $line) {
            if ($multiline) {
                if (preg_match('/^(.*)"\s*$/', $line, $m)) {
                    $currentValue[2] .= $m[1];
                    $multiline = false;
                    $this->content[$currentSection][] = $currentValue;
                } else {
                    $currentValue[2] .= $line."\n";
                }
            } elseif (preg_match('/^\s*([\\w0-9_.\\-]+)(\\[[^\\[\\]]*\\])?\s*=\s*(")?([^"]*)(")?(\s*)/ui', $line, $m)) {
                list($all, $name, $foundkey, $firstquote, $value, $secondquote, $lastspace) = $m;

                if ($foundkey != '') {
                    $key = substr($foundkey, 1, -1);
                    if ($key == '') {
                        if (isset($arrayContents[$currentSection][$name])) {
                            $key = count($arrayContents[$currentSection][$name]);
                        } else {
                            $key = 0;
                        }
                    }
                    $currentValue = array(self::TK_ARR_VALUE, $name, $value, $key);
                    $arrayContents[$currentSection][$name][$key] = $value;
                } else {
                    $currentValue = array(self::TK_VALUE, $name, $value);
                }

                if ($firstquote == '"' && $secondquote == '') {
                    $multiline = true;
                    $currentValue[2] .= "\n";
                } else {
                    if ($firstquote == '' && $secondquote == '') {
                        $currentValue[2] = trim($value);
                    }
                    $this->content[$currentSection][] = $currentValue;
                }
            } elseif (preg_match('/^(\\s*;.*)$/', $line, $m)) {
                $this->content[$currentSection][] = array(self::TK_COMMENT, $m[1]);
            } elseif (preg_match('/^(\\s*\\[([^\\]]+)\\]\\s*)/ui', $line, $m)) {
                if (strpos($m[2], ';')) {
                    // ';' is forbidden in the name as it begins a comment
                    throw new \DomainException("Invalid syntax for the section name: \"".$m[2].'"');
                }
                $currentSection = $m[2];
                $this->content[$currentSection] = array(
                    array(self::TK_SECTION, $m[1]),
                );
            } else {
                $this->content[$currentSection][] = array(self::TK_WS, $line);
            }
        }
    }

    /**
     * return the value of an option in the ini file. If the option doesn't exist,
     * it returns null.
     *
     * @param string $name    the name of the option to retrieve
     * @param string $section the section where the option is. 0 is the global section
     * @param int    $key     for option which is an item of array, the key in the array
     *
     * @return mixed|null the value
     */
    public function getValue($name, $section = 0, $key = null)
    {
        if (!isset($this->content[$section])) {
            return null;
        }
        $arrayValue = array();
        $isArray = false;
        foreach ($this->content[$section] as $k => $item) {
            if (($item[0] != self::TK_VALUE && $item[0] != self::TK_ARR_VALUE)
                || $item[1] != $name) {
                continue;
            }
            if ($item[0] == self::TK_ARR_VALUE) {
                if ($key !== null) {
                    if ($item[3] != $key) {
                        continue;
                    }
                } else {
                    $isArray = true;
                    $arrayValue[$item[3]] = $this->convertValue($item[2]);
                    continue;
                }
            }
            return $this->convertValue($item[2]);
        }
        if ($isArray) {
            return $arrayValue;
        }

        return null;
    }

    /**
     * return all values of a section in the ini file.
     *
     * @param string $section the section from which we want values. 0 is the global section
     *
     * @return array the list of values, $key=>$value
     */
    public function getValues($section = 0)
    {
        if (!isset($this->content[$section])) {
            return array();
        }
        $values = array();
        foreach ($this->content[$section] as $k => $item) {
            if ($item[0] != self::TK_VALUE && $item[0] != self::TK_ARR_VALUE) {
                continue;
            }

            $val = $this->convertValue($item[2]);

            if ($item[0] == self::TK_VALUE) {
                $values[$item[1]] = $val;
            } else {
                $values[$item[1]][$item[3]] = $val;
            }
        }

        return $values;
    }

    protected function convertValue($value) {
        if (!is_string($value)) {
            // values that are set after the parsing, may be PHP raw values...
            return $value;
        }
        if (preg_match('/^-?[0-9]$/', $value)) {
            return intval($value);
        } elseif (preg_match('/^-?[0-9\.]$/', $value)) {
            return floatval($value);
        } elseif (strtolower($value) === 'true' || strtolower($value) === 'on' || strtolower($value) === 'yes') {
            return true;
        } elseif (strtolower($value) === 'false' || strtolower($value) === 'off' || strtolower($value) === 'no' || strtolower($value) === 'none') {
            return false;
        }
        return $value;
    }


    /**
     * says if there is a section with the given name.
     *
     * @since 1.2
     */
    public function isSection($name)
    {
        return isset($this->content[$name]);
    }

    /**
     * return the list of section names.
     *
     * @return array
     *
     * @since 1.2
     */
    public function getSectionList()
    {
        $list = array_keys($this->content);
        array_shift($list); // remove the global section
        return $list;
    }
}
