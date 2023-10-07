<?php

/**
 * @author     Laurent Jouanneau
 * @copyright  2008-2018 Laurent Jouanneau
 *
 * @link       http://jelix.org
 * @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\IniFile;

/**
 * utility class to read and modify two ini files at the same time :
 * one master file, and one file which overrides values of the master file,
 * like we have in jelix with mainconfig.ini.php and config.ini.php of an entry point.
 */
class MultiIniModifier implements IniModifierInterface
{
    /**
     * @var \Jelix\IniFile\IniReaderInterface
     */
    protected $master;

    /**
     * @var \Jelix\IniFile\IniModifier
     */
    protected $overrider;

    /**
     * load the two ini files.
     *
     * @param \Jelix\IniFile\IniReaderInterface|string   $master    the master ini file (object or filename)
     * @param \Jelix\IniFile\IniModifierInterface|string $overrider the ini file overriding the master ini file (object or filename)
     */
    public function __construct($master, $overrider)
    {
        if (is_object($master)) {
            $this->master = $master;
        } else {
            $this->master = new IniModifier($master);
        }
        if (is_object($overrider)) {
            $this->overrider = $overrider;
        } else {
            $this->overrider = new IniModifier($overrider);
        }
    }

    public function isEmpty()
    {
        return $this->master->isEmpty() && $this->overrider->isEmpty();
    }

    public function getFileName()
    {
        return $this->overrider->getFileName();
    }

    /**
     * modify an option in the overrider ini file. If the option doesn't exist,
     * it is created.
     *
     * @param string $name    the name of the option to modify
     * @param string $value   the new value
     * @param string $section the section where to set the item. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     */
    public function setValue($name, $value, $section = 0, $key = null)
    {
        $this->overrider->setValue($name, $value, $section, $key);
    }

    /**
     * modify an option in the master ini file. If the option doesn't exist,
     * it is created.
     *
     * @param string $name    the name of the option to modify
     * @param string $value   the new value
     * @param string $section the section where to set the item. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     */
    public function setValueOnMaster($name, $value, $section = 0, $key = null)
    {
        if (!$this->master instanceof IniModifierInterface) {
            throw new IniException('Cannot set value on master which is only an ini reader');
        }
        $this->master->setValue($name, $value, $section, $key);
    }

    /**
     * modify several options in the overrider ini file.
     *
     * @param array  $value   associated array with key=>value
     * @param string $section the section where to set the item. 0 is the global section
     */
    public function setValues($values, $section = 0)
    {
        $this->overrider->setValues($values, $section);
    }

    /**
     * modify several options in the master ini file.
     *
     * @param array  $value   associated array with key=>value
     * @param string $section the section where to set the item. 0 is the global section
     */
    public function setValuesOnMaster($values, $section = 0)
    {
        if (!$this->master instanceof IniModifierInterface) {
            throw new IniException('Cannot set value on master which is only an ini reader');
        }
        $this->master->setValues($values, $section);
    }

    /**
     * return the value of an option from the ini files. If the option doesn't exist,
     * it returns null.
     *
     * @param string $name    the name of the option to retrieve
     * @param string $section the section where the option is. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     *
     * @return mixed the value
     */
    public function getValue($name, $section = 0, $key = null)
    {
        $val = $this->overrider->getValue($name, $section, $key);
        if ($val === null) {
            $val = $this->master->getValue($name, $section, $key);
        }

        return $val;
    }

    /**
     * return the value of an option from the master ini file only.
     * If the option doesn't exist, it returns null.
     *
     * @param string $name    the name of the option to retrieve
     * @param string $section the section where the option is. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     *
     * @return mixed the value
     */
    public function getValueOnMaster($name, $section = 0, $key = null)
    {
        return $this->master->getValue($name, $section, $key);
    }

    /**
     * return all values of a section from the both ini files.
     *
     * @param string $section the section from wich we want values. 0 is the global section
     *
     * @return array the list of values, $key=>$value
     */
    public function getValues($section = 0)
    {
        $masterValues = $this->master->getValues($section);
        $overValues = $this->overrider->getValues($section);

        foreach ($overValues as $key => &$value) {
            if (!isset($masterValues[$key])) {
                $masterValues[$key] = $value;
                continue;
            }
            if (is_array($value) && is_array($masterValues[$key])) {
                $masterValues[$key] = array_merge($masterValues[$key], $value);
            } else {
                $masterValues[$key] = $value;
            }
        }

        return $masterValues;
    }

    /**
     * remove an option from the two ini file.
     *
     * It can remove an entire section if you give an empty value as $name,
     * and a $section name. (deprecated behavior, see removeSection())
     *
     *
     * @param string $name                  the name of the option to remove, or null to remove an entire section
     * @param string $section               the section where to remove the value, or the section to remove
     * @param int    $key                   for option which is an item of array, the key in the array
     * @param bool   $removePreviousComment if a comment is before the value, if true, it removes also the comment
     */
    public function removeValue($name, $section = 0, $key = null, $removePreviousComment = true)
    {
        if ($this->master instanceof IniModifierInterface) {
            $this->master->removeValue($name, $section, $key, $removePreviousComment);
        }
        $this->overrider->removeValue($name, $section, $key, $removePreviousComment);
    }

    /**
     * remove an option from the master ini file only.
     *
     * It can remove an entire section if you give an empty value as $name,
     * and a $section name. (deprecated behavior, see removeSection())
     *
     * @param string $name                  the name of the option to remove, or null to remove an entire section
     * @param string $section               the section where to remove the value, or the section to remove
     * @param int    $key                   for option which is an item of array, the key in the array
     * @param bool   $removePreviousComment if a comment is before the value, if true, it removes also the comment
     */
    public function removeValueOnMaster($name, $section = 0, $key = null, $removePreviousComment = true)
    {
        if (!$this->master instanceof IniModifierInterface) {
            throw new IniException('Cannot remove value on master which is only an ini reader');
        }
        $this->master->removeValue($name, $section, $key, $removePreviousComment);
    }


    /**
     * remove a section from the two ini file.
     *
     * @param int $section
     * @param bool $removePreviousComment
     * @since 2.5.0
     */
    public function removeSection($section = 0, $removePreviousComment = true)
    {
        if ($this->master instanceof IniModifierInterface) {
            $this->master->removeSection($section, $removePreviousComment);
        }
        $this->overrider->removeSection($section, $removePreviousComment);
    }

    /**
     * remove a section from the two ini file.
     *
     * @param int $section
     * @param bool $removePreviousComment
     * @since 2.5.0
     */
    public function removeSectionOnMaster($section = 0, $removePreviousComment = true)
    {
        if (!$this->master instanceof IniModifierInterface) {
            throw new IniException('Cannot remove a section on master which is only an ini reader');
        }

        $this->master->removeSection($section, $removePreviousComment);
    }

    /**
     * save the ini files.
     */
    public function save($chmod = null, $format = 0)
    {
        if ($this->master instanceof IniModifierInterface) {
            $this->master->save($chmod, $format);
        }
        $this->overrider->save($chmod, $format);
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
        if ($this->master instanceof IniModifierInterface) {
            return $this->master->isModified() || $this->overrider->isModified();
        }

        return $this->overrider->isModified();
    }

    /**
     * @return \Jelix\IniFile\IniReaderInterface the first ini file
     *
     * @since 1.2
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @return \Jelix\IniFile\IniModifierInterface the second ini file
     *
     * @since 1.2
     */
    public function getOverrider()
    {
        return $this->overrider;
    }

    /**
     * says if there is a section with the given name.
     */
    public function isSection($name)
    {
        return $this->overrider->isSection($name) || $this->master->isSection($name);
    }

    /**
     * return the list of section names.
     *
     * @return array
     */
    public function getSectionList()
    {
        $list = array_merge($this->master->getSectionList(), $this->overrider->getSectionList());
        return array_unique($list);
    }
}
