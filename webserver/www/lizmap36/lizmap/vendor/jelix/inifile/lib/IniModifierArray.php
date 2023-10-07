<?php

/**
 * @author     Laurent Jouanneau
 * @copyright  2017-2018 Laurent Jouanneau
 *
 * @link       http://jelix.org
 * @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\IniFile;

/**
 * utility class to read and modify several files at the same time. It acts
 * as if all ini files were merged, with a priority: the latest ini content
 * has priority over the first ini content.
 */
class IniModifierArray implements IniModifierInterface, \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var \Jelix\IniFile\IniReaderInterface[]
     */
    protected $modifiers;

    /**
     * @var \Jelix\IniFile\IniReaderInterface[]
     */
    protected $reversedModifiers;

    /**
     * @var \Jelix\IniFile\IniReaderInterface
     */
    protected $lastModifier;

    /**
     * @var mixed
     */
    protected $lastModifierKey;

    /**
     * set all modifiers objects. The last one has priority to the first one.
     *
     * @param \Jelix\IniFile\IniReaderInterface[]|string[] $modifiers the list of ini file names or ini reader/modifier objects
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $k => $modifier) {
            $modifiers[$k] = $this->checkValue('A value from the array', $modifier);
        }
        $this->modifiers = $modifiers;
        $this->setReversedArray();
    }

    protected function checkValue($label, $modifier)
    {
        if (is_string($modifier)) {
            return new IniModifier($modifier);
        } elseif (!is_object($modifier)) {
            throw new IniInvalidArgumentException($label.' is not a string or a \\Jelix\\IniFile\\IniModifierInterface object');
        } elseif (!$modifier instanceof \Jelix\IniFile\IniModifierInterface &&
                 !$modifier instanceof \Jelix\IniFile\IniReaderInterface) {
            throw new IniInvalidArgumentException($label.' is not a \\Jelix\\IniFile\\IniModifierInterface object');
        }

        return $modifier;
    }

    protected function setReversedArray()
    {
        $this->reversedModifiers = array_reverse($this->modifiers);
        reset($this->reversedModifiers);
        $this->lastModifierKey = key($this->reversedModifiers);
        $this->lastModifier = current($this->reversedModifiers);
    }

    // ---------------------------------------------- IniModifierInterface


    public function isEmpty()
    {
        foreach ($this->modifiers as $k => $modifier) {
            if (!$modifier->isEmpty()) {
                return false;
            }
        }
        return true;
    }

    public function getFileName()
    {
        return $this->lastModifier->getFileName();
    }

    /**
     * modify an option in the latest ini file. If the option doesn't exist,
     * it is created.
     *
     * @param string $name    the name of the option to modify
     * @param string $value   the new value
     * @param string $section the section where to set the item. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     */
    public function setValue($name, $value, $section = 0, $key = null)
    {
        if ($this->lastModifier instanceof IniModifierInterface) {
            $this->lastModifier->setValue($name, $value, $section, $key);
        } else {
            trigger_error('The top ini content is not alterable', E_USER_WARNING);
        }
    }

    /**
     * modify several options in the latest ini file.
     *
     * @param array  $value   associated array with key=>value
     * @param string $section the section where to set the item. 0 is the global section
     */
    public function setValues($values, $section = 0)
    {
        if ($this->lastModifier instanceof IniModifierInterface) {
            $this->lastModifier->setValues($values, $section);
        } else {
            trigger_error('The top ini content is not alterable', E_USER_WARNING);
        }
    }

    /**
     * return the value of an option from the ini files. If the option doesn't exist,
     * it returns null.
     *
     * @param string $name    the name of the option to retrieve
     * @param string $section the section where the option is. 0 is the global section
     * @param string $key     for option which is an item of array, the key in the array
     *
     * @return mixed|null the value
     */
    public function getValue($name, $section = 0, $key = null)
    {
        foreach ($this->reversedModifiers as $mod) {
            $val = $mod->getValue($name, $section, $key);
            if ($val !== null) {
                return $val;
            }
        }

        return null;
    }

    /**
     * return all values of a section from all ini files.
     *
     * @param string $section the section from wich we want values. 0 is the global section
     *
     * @return array the list of values, $key=>$value
     */
    public function getValues($section = 0)
    {
        $finalValues = array();
        foreach ($this->modifiers as $mod) {
            $values = $mod->getValues($section);
            if (!count($values)) {
                continue;
            }
            if (!count($finalValues)) {
                $finalValues = $values;
                continue;
            }
            foreach ($values as $key => &$value) {
                if (!isset($finalValues[$key])) {
                    $finalValues[$key] = $value;
                    continue;
                }
                if (is_array($value) && is_array($finalValues[$key])) {
                    $finalValues[$key] = array_merge($finalValues[$key], $value);
                } else {
                    $finalValues[$key] = $value;
                }
            }
        }

        return $finalValues;
    }

    /**
     * remove an option from all ini file.
     *
     * It can remove an entire section if you give an empty value as $name, and a $section name.
     * (deprecated behavior, see removeSection())
     *
     * @param string $name                  the name of the option to remove, or null to remove an entire section
     * @param string $section               the section where to remove the value, or the section to remove
     * @param int    $key                   for option which is an item of array, the key in the array
     * @param bool   $removePreviousComment if a comment is before the value, if true, it removes also the comment
     */
    public function removeValue($name, $section = 0, $key = null, $removePreviousComment = true)
    {
        foreach ($this->modifiers as $mod) {
            if ($mod instanceof IniModifierInterface) {
                $mod->removeValue($name, $section, $key, $removePreviousComment);
            }
        }
    }

    /**
     * remove a section from all ini file.
     * @param int $section
     * @param bool $removePreviousComment
     * @since 2.5.0
     */
    public function removeSection($section = 0, $removePreviousComment = true)
    {
        foreach ($this->modifiers as $mod) {
            if ($mod instanceof IniModifierInterface) {
                $mod->removeSection($section, $removePreviousComment);
            }
        }
    }

    /**
     * save the ini files.
     */
    public function save($chmod = null, $format = 0)
    {
        foreach ($this->modifiers as $mod) {
            if ($mod instanceof IniModifierInterface) {
                $mod->save($chmod, $format);
            }
        }
    }

    /**
     * says if the ini content has been modified.
     *
     * @return bool
     */
    public function isModified()
    {
        foreach ($this->modifiers as $mod) {
            if ($mod instanceof IniModifierInterface && $mod->isModified()) {
                return true;
            }
        }

        return false;
    }

    public function isSection($name)
    {
        foreach ($this->modifiers as $mod) {
            if ($mod->isSection($name)) {
                return true;
            }
        }
        return false;
    }

    /**
     * return the list of section names.
     *
     * @return array
     */
    public function getSectionList()
    {
        $list = [];
        foreach ($this->modifiers as $mod) {
            $list = array_merge($list, $mod->getSectionList());
        }
        return array_unique($list);
    }

    // ---------------------------------------------- \IteratorAggregate

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->modifiers);
    }

    // ---------------------------------------------- \ArrayAccess

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $value = $this->checkValue('A given value', $value);
        if (is_null($offset)) {
            $this->modifiers[] = $value;
        } else {
            $this->modifiers[$offset] = $value;
        }
        $this->setReversedArray();
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->modifiers[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->modifiers[$offset]);
        $this->setReversedArray();
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->modifiers[$offset]) ? $this->modifiers[$offset] : null;
    }

    // ---------------------------------------------- \Countable
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->modifiers);
    }
}
