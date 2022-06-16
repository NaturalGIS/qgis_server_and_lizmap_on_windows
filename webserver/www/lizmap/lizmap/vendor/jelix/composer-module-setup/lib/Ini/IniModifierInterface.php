<?php

/**
 * @author     Laurent Jouanneau
 * @copyright  2016-2020 Laurent Jouanneau
 *
 * @link       http://jelix.org
 * @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\ComposerPlugin\Ini;

/**
 * Interface for classes that allow to modify an ini file.
 */
interface IniModifierInterface extends IniReaderInterface
{
    /**
     * modify an option in the ini file. If the option doesn't exist,
     * it is created.
     *
     * @param string $name    the name of the option to modify
     * @param mixed  $value   the new value
     * @param string $section the section where to set the item. 0 is the global section
     * @param int    $key     for option which is an item of array, the key in the array. '' to just add a value in the array
     */
    public function setValue($name, $value, $section = 0, $key = null);

    /**
     * modify several options in the ini file.
     *
     * @param array  $value   associated array with key=>value
     * @param string $section the section where to set the item. 0 is the global section
     */
    public function setValues($values, $section = 0);

    /**
     * remove an option in the ini file.
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
    public function removeValue($name, $section = 0, $key = null, $removePreviousComment = true);

    /**
     * remove a section from the ini file.
     *
     * @param string $section the section where to remove the value, or the section to remove
     *
     * @since 2.5.0
     */
    public function removeSection($section = 0, $removePreviousComment = true);

    /**
     * save the ini file.
     */
    public function save($chmod = null);

    /**
     * says if the ini content has been modified.
     *
     * @return bool
     *
     * @since 1.2
     */
    public function isModified();
}
