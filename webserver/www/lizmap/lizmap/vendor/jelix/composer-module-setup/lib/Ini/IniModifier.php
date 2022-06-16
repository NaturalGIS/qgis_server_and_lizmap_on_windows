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
 * utility class to modify an ini file by preserving comments, whitespace..
 * It follows same behaviors of parse_ini_file, except when there are quotes
 * inside values. it doesn't support quotes inside values, because parse_ini_file
 * is totally bugged, depending cases.
 */
class IniModifier extends IniReader implements IniModifierInterface
{
    /**
     * @var bool true if the content has been modified
     */
    protected $modified = false;

    /**
     * IniModifier constructor.
     *
     * @param string $filename       the file from which the content should be readed/written
     * @param string $initialContent if the file does not exists, it takes the given content
     *                               as initial content.
     */
    public function __construct($filename, $initialContent = '')
    {
        if (!$filename) {
            throw new \InvalidArgumentException('Filename should not be empty');
        }
        $this->filename = $filename;
        if (file_exists($filename) && is_file($filename)) {
            $this->parse(preg_split("/(\r\n|\n|\r)/", file_get_contents($filename)));
        } elseif ($initialContent != '') {
            $this->parse(preg_split("/(\r\n|\n|\r)/", $initialContent));
        }
    }

    /**
     * modify an option in the ini file. If the option doesn't exist,
     * it is created.
     *
     * @param string $name    the name of the option to modify
     * @param string $value   the new value
     * @param string $section the section where to set the item. 0 is the global section
     * @param int    $key     for option which is an item of array, the key in the array. '' to just add a value in the array
     */
    public function setValue($name, $value, $section = 0, $key = null)
    {
        if (!preg_match('/^[^\\[\\]]*$/', $name)) {
            throw new \InvalidArgumentException("Invalid value name $name");
        }

        if (is_array($value)) {
            if ($key !== null) {
                throw new \InvalidArgumentException('You cannot indicate a key for an array value');
            }
            $this->_setArrayValue($name, $value, $section);
        } else {
            $this->_setValue($name, $value, $section, $key);
        }
    }

    protected function _setValue($name, $value, $section = 0, $key = null)
    {
        if (is_string($key) && !preg_match('/^[^\\[\\]]*$/', $key)) {
            throw new \InvalidArgumentException("Invalid key $key for the value $name");
        }
        $foundValue = false;
        $lastKey = -1; // last key in an array value
        if (isset($this->content[$section])) {
            // boolean to erase array values if the new value is not a new item for the array
            $deleteMode = false;
            foreach ($this->content[$section] as $k => $item) {
                if ($deleteMode) {
                    if ($item[0] == self::TK_ARR_VALUE && $item[1] == $name) {
                        $this->content[$section][$k] = array(self::TK_WS, '--');
                        $this->modified = true;
                    }
                    continue;
                }

                // if the item is not a value or an array value, or not the same name
                if (($item[0] != self::TK_VALUE && $item[0] != self::TK_ARR_VALUE)
                    || $item[1] != $name) {
                    continue;
                }

                // if it is an array value, and if the key doesn't correspond
                if ($item[0] == self::TK_ARR_VALUE && $key !== null) {
                    if ($item[3] !== $key || $key === '') {
                        if (is_numeric($item[3])) {
                            $lastKey = $item[3];
                        }
                        continue;
                    }
                }
                if ($key !== null) {
                    // we add the value as an array value
                    if ($key === '') {
                        $key = 0;
                    }
                    if (!$this->_compareNewValue($item[2], $value)) {
                        $this->content[$section][$k] = array(self::TK_ARR_VALUE, $name, $value, $key);
                        $this->modified = true;
                    }
                } else {
                    // we store the value
                    if (!$this->_compareNewValue($item[2], $value)) {
                        $this->content[$section][$k] = array(self::TK_VALUE, $name, $value);
                        $this->modified = true;
                    }
                    if ($item[0] == self::TK_ARR_VALUE) {
                        // the previous value was an array value, so we erase other array values
                        $deleteMode = true;
                        $foundValue = true;
                        continue;
                    }
                }
                $foundValue = true;
                break;
            }
        } else {
            $this->content[$section] = array(array(self::TK_SECTION, '['.$section.']'));
        }
        if (!$foundValue) {
            if ($key === null) {
                $this->content[$section][] = array(self::TK_VALUE, $name, $value);
            } else {
                if ($key === '') {
                    if ($lastKey != -1) {
                        $key = ++$lastKey;
                    } else {
                        $key = 0;
                    }
                }
                $this->content[$section][] = array(self::TK_ARR_VALUE, $name, $value, $key);
            }
            $this->modified = true;
        }
    }

    protected function _compareNewValue($iniValue, $newValue) {
        $iniVal = $this->convertValue($iniValue);
        $newVal = $this->convertValue($newValue);
        return ($iniVal == $newVal);
    }


    protected function _setArrayValue($name, $value, $section = 0)
    {
        $foundKeys = array_combine(
            array_keys($value),
            array_fill(0, count($value), false)
        );
        if (isset($this->content[$section])) {
            foreach ($this->content[$section] as $k => $item) {
                // if the item is not a value or an array value, or not the same name
                if (($item[0] != self::TK_VALUE && $item[0] != self::TK_ARR_VALUE)
                    || $item[1] != $name) {
                    continue;
                }

                if ($item[0] == self::TK_ARR_VALUE) {
                    if (isset($value[$item[3]])) {
                        $foundKeys[$item[3]] = true;
                        $this->content[$section][$k][2] = $value[$item[3]];
                    } else {
                        $this->content[$section][$k] = array(self::TK_WS, '--');
                    }
                } else {
                    $this->content[$section][$k] = array(self::TK_WS, '--');
                }
            }
        } else {
            $this->content[$section] = array(array(self::TK_SECTION, '['.$section.']'));
        }

        foreach ($value as $k => $v) {
            if (!$foundKeys[$k]) {
                $this->content[$section][] = array(self::TK_ARR_VALUE, $name, $v, $k);
            }
        }
        $this->modified = true;
    }

    /**
     * modify several options in the ini file.
     *
     * @param array  $value   associated array with key=>value
     * @param string $section the section where to set the item. 0 is the global section
     */
    public function setValues($values, $section = 0)
    {
        foreach ($values as $name => $val) {
            $this->setValue($name, $val, $section);
        }
    }

    /**
     * remove an option from the ini file.
     *
     * It can remove an entire section if you give an empty value as $name,
     * and a $section name. (deprecated behavior, see removeSection())
     *
     * @param string $name    the name of the option to remove, or null to remove an entire section
     * @param string $section the section where to remove the value, or the section to remove
     * @param int    $key     for option which is an item of array, the key in the array
     *
     * @since 1.2
     */
    public function removeValue($name, $section = 0, $key = null, $removePreviousComment = true)
    {
        if ($section === 0 && $name == '') {
            return;
        }

        if ($name == '') {
            $this->removeSection($section, $removePreviousComment);
            return;
        }

        if (isset($this->content[$section])) {
            // boolean to erase array values if the option to remove is an array
            $deleteMode = false;
            $previousComment = array();
            foreach ($this->content[$section] as $k => $item) {
                if ($deleteMode) {
                    if ($item[0] == self::TK_ARR_VALUE && $item[1] == $name) {
                        $this->content[$section][$k] = array(self::TK_WS, '--');
                    }
                    continue;
                }

                if ($item[0] == self::TK_COMMENT) {
                    if ($removePreviousComment) {
                        $previousComment[] = $k;
                    }
                    continue;
                }

                if ($item[0] == self::TK_WS) {
                    if ($removePreviousComment) {
                        $previousComment[] = $k;
                    }
                    continue;
                }

                // if the item is not a value or an array value, or not the same name
                if ($item[1] != $name) {
                    $previousComment = array();
                    continue;
                }

                // if it is an array value, and if the key doesn't correspond
                if ($item[0] == self::TK_ARR_VALUE && $key !== null) {
                    if ($item[3] != $key) {
                        $previousComment = array();
                        continue;
                    }
                }
                $this->modified = true;
                if (count($previousComment)) {
                    $kc = array_pop($previousComment);
                    while ($kc !== null && $this->content[$section][$kc][0] == self::TK_WS) {
                        $kc = array_pop($previousComment);
                    }

                    while ($kc !== null && $this->content[$section][$kc][0] == self::TK_COMMENT) {
                        if (strpos($this->content[$section][$kc][1], '<?') === false) {
                            $this->content[$section][$kc] = array(self::TK_WS, '--');
                        }
                        $kc = array_pop($previousComment);
                    }
                }
                if ($key !== null) {
                    // we remove the value from the array
                    $this->content[$section][$k] = array(self::TK_WS, '--');
                } else {
                    // we remove the value
                    $this->content[$section][$k] = array(self::TK_WS, '--');
                    if ($item[0] == self::TK_ARR_VALUE) {
                        // the previous value was an array value, so we erase other array values
                        $deleteMode = true;
                        continue;
                    }
                }
                break;
            }
        }
    }

    /**
     * remove a section from the ini file.
     *
     * @param string $section the section where to remove the value, or the section to remove
     *
     * @since 2.5.0
     */
    public function removeSection($section = 0, $removePreviousComment = true)
    {
        if ($section === 0 || !isset($this->content[$section])) {
            return;
        }

        if ($removePreviousComment) {
            // retrieve the previous section
            $previousSection = -1;
            foreach ($this->content as $s => $c) {
                if ($s === $section) {
                    break;
                } else {
                    $previousSection = $s;
                }
            }

            if ($previousSection != -1) {
                //retrieve the last comment
                $s = $this->content[$previousSection];
                end($s);
                $tok = current($s);
                while ($tok !== false) {
                    if ($tok[0] != self::TK_WS && $tok[0] != self::TK_COMMENT) {
                        break;
                    }
                    if ($tok[0] == self::TK_COMMENT && strpos($tok[1], '<?') === false) {
                        $this->content[$previousSection][key($s)] = array(self::TK_WS, '--');
                    }
                    $tok = prev($s);
                }
            }
        }

        unset($this->content[$section]);
        $this->modified = true;
    }

    /**
     * save the ini file.
     */
    public function save($chmod = null)
    {
        if ($this->modified) {
            if (false === @file_put_contents($this->filename, $this->generateIni())) {
                throw new \Exception('Impossible to write into '.$this->filename);
            } elseif ($chmod) {
                chmod($this->filename, $chmod);
            }
            $this->modified = false;
        }
    }

    /**
     * save the content in an new ini file.
     *
     * @param string $filename the name of the file
     */
    public function saveAs($filename)
    {
        file_put_contents($filename, $this->generateIni());
    }

    /**
     * says if the ini content has been modified.
     *
     * @return bool
     *
     * @since 1.2
     */
    public function isModified()
    {
        return $this->modified;
    }

    protected function generateIni()
    {
        $content = '';
        $lastToken = null;
        foreach ($this->content as $sectionname => $section) {
            foreach ($section as $item) {
                $lastToken = $item[0];
                switch ($item[0]) {
                    case self::TK_SECTION:
                        if ($item[1] != '0') {
                            $content .= $item[1]."\n";
                        }
                        break;
                    case self::TK_WS:
                        if ($item[1] == '--') {
                            break;
                        }
                    // no break
                    case self::TK_COMMENT:
                        $content .= $item[1]."\n";
                        break;
                    case self::TK_VALUE:
                        $content .= $item[1].'='.$this->getIniValue($item[2])."\n";
                        break;
                    case self::TK_ARR_VALUE:
                        if (is_numeric($item[3])) {
                            $content .= $item[1].'[]='.$this->getIniValue($item[2])."\n";
                        } else {
                            $content .= $item[1].'['.$item[3].']='.$this->getIniValue($item[2])."\n";
                        }

                        break;
                }
            }
        }
        if ($lastToken === self::TK_WS) {
            // remove the last \n
            $content = substr($content, 0, -1);
        }

        return $content;
    }

    protected function getIniValue($value)
    {
        if (is_bool($value)) {
            if ($value === false) {
                return "off";
            } else {
                return "on";
            }
        }
        if ($value === '' ||
            is_numeric(trim($value)) ||
            (is_string($value) && preg_match('/^[\\w\\-\\.]*$/u', $value) &&
                strpos("\n", $value) === false)
        ) {
            return $value;
        } else {
            $value = '"'.$value.'"';
        }

        return $value;
    }

    /**
     * import values of an ini file into the current ini content.
     * If a section prefix is given, all section of the given ini file will be
     * renamed with the prefix plus "_". The global (unamed) section will be the section
     * named with the value of prefix. If the section prefix is not given, the existing
     * sections and given section with the same name will be merged.
     *
     * @param \Jelix\ComposerPlugin\IniReaderInterface $ini           an ini file modifier to merge with the current
     * @param string                            $sectionPrefix the prefix to add to the section prefix
     * @param string                            $separator     the separator to add between the prefix and the old name
     *                                                         of the section
     *
     * @since 1.2
     */
    public function import(IniReaderInterface $ini, $sectionPrefix = '', $separator = '_')
    {
        foreach ($ini->content as $section => $values) {
            if ($sectionPrefix) {
                if ($section == '0') {
                    $realSection = $sectionPrefix;
                } else {
                    $realSection = $sectionPrefix.$separator.$section;
                }
            } else {
                $realSection = $section;
            }

            if (isset($this->content[$realSection])) {
                // let's merge the current and the given section
                $this->mergeValues($values, $realSection);
            } else {
                if ($values[0][0] == self::TK_SECTION) {
                    $values[0][1] = '['.$realSection.']';
                } else {
                    array_unshift($values, array(self::TK_SECTION, '['.$realSection.']'));
                }
                $this->content[$realSection] = $values;
                $this->modified = true;
            }
        }
    }

    /**
     * move values of a section into an other section and remove the section.
     *
     * @return bool true if the merge is a success
     */
    public function mergeSection($sectionSource, $sectionTarget)
    {
        if (!isset($this->content[$sectionTarget])) {
            return $this->renameSection($sectionSource, $sectionTarget);
        }

        if (!isset($this->content[$sectionSource])) {
            return false;
        }
        $this->mergeValues($this->content[$sectionSource], $sectionTarget);
        if ($sectionSource == '0') {
            $this->content[$sectionSource] = array();
        } else {
            unset($this->content[$sectionSource]);
        }
        $this->modified = true;

        return true;
    }

    protected function mergeValues($values, $sectionTarget)
    {
        $previousItems = array();
        $arrayValuesToReplace = array();
        // if options already exists, just change their values.
        // if options don't exist, add them to the section, with
        // comments and whitespace
        foreach ($values as $k => $item) {
            switch ($item[0]) {
                case self::TK_SECTION:
                    break;
                case self::TK_WS:
                    if ($item[1] == '--') {
                        break;
                    }
                // no break
                case self::TK_COMMENT:
                    $previousItems [] = $item;
                    break;
                case self::TK_VALUE:
                case self::TK_ARR_VALUE:
                    $found = false;
                    $lastNonValues = -1;
                    foreach ($this->content[$sectionTarget] as $j => $item2) {
                        if ($item2[0] != self::TK_VALUE && $item2[0] != self::TK_ARR_VALUE) {
                            if ($lastNonValues == -1 && $item2[0] != self::TK_SECTION) {
                                $lastNonValues = $j;
                            }
                            continue;
                        }
                        if ($item2[1] != $item[1]) {
                            $lastNonValues = -1;
                            continue;
                        }
                        if ($item[0] == self::TK_ARR_VALUE && $item2[0] == $item[0]) {
                            if ($item[3] !== $item2[3]) {
                                $lastNonValues = -1;
                                continue;
                            }
                        }

                        $found = true;
                        $this->modified = true;
                        if ($item2[0] != $item[0]) {
                            // same name, but not the same type
                            if ($item2[0] == self::TK_VALUE) {
                                $this->content[$sectionTarget][$j] = $item;
                            } else {
                                $arrayValuesToReplace[$item[1]] = $item[2];
                            }
                            continue;
                        }
                        $this->content[$sectionTarget][$j][2] = $item[2];
                        break;
                    }
                    if (!$found) {
                        $previousItems[] = $item;
                        if ($lastNonValues > 0) {
                            $previousItems = array_splice($this->content[$sectionTarget], $lastNonValues, $j, $previousItems);
                        }
                        $this->content[$sectionTarget] = array_merge($this->content[$sectionTarget], $previousItems);
                        $this->modified = true;
                    }
                    $previousItems = array();
                    break;
            }
        }
        foreach ($arrayValuesToReplace as $name => $value) {
            $this->setValue($name, $value, $sectionTarget);
        }
    }

    /**
     * rename a value.
     */
    public function renameValue($name, $newName, $section = 0)
    {
        if (!isset($this->content[$section])) {
            return false;
        }
        foreach ($this->content[$section] as $k => $item) {
            if ($item[0] != self::TK_VALUE && $item[0] != self::TK_ARR_VALUE) {
                continue;
            }
            if ($item[1] != $name) {
                continue;
            }
            $this->content[$section][$k][1] = $newName;
            $this->modified = true;
            if ($item[0] == self::TK_VALUE) {
                break;
            }
        }

        return true;
    }

    /**
     * rename a section.
     */
    public function renameSection($oldName, $newName)
    {
        if (!isset($this->content[$oldName])) {
            return false;
        }

        if (isset($this->content[$newName])) {
            return $this->mergeSection($oldName, $newName);
        }

        $newcontent = array();
        foreach ($this->content as $section => $values) {
            if ((string) $oldName == (string) $section) {
                if ($section == '0') {
                    $newcontent[0] = array();
                }
                if ($values[0][0] == self::TK_SECTION) {
                    $values[0][1] = '['.$newName.']';
                } else {
                    array_unshift($values, array(self::TK_SECTION, '['.$newName.']'));
                }
                $newcontent[$newName] = $values;
            } else {
                $newcontent [$section] = $values;
            }
        }
        $this->content = $newcontent;
        $this->modified = true;

        return true;
    }
}
