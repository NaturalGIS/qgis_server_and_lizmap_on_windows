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
 * Interface for classes that allow to read an ini file.
 */
interface IniReaderInterface
{

    /**
     * @return boolean true if there is no content
     */
    public function isEmpty();

    /**
     * @return string the file name
     */
    public function getFileName();

    /**
     * return the value of an option in the ini file. If the option doesn't exist,
     * it returns null.
     *
     * @param string $name    the name of the option to retrieve
     * @param string $section the section where the option is. 0 is the global section
     * @param int    $key     for option which is an item of array, the key in the array
     *
     * @return mixed the value
     */
    public function getValue($name, $section = 0, $key = null);

    /**
     * return all values of a section in the ini file.
     *
     * @param string $section the section from wich we want values. 0 is the global section
     *
     * @return array the list of values, $key=>$value
     */
    public function getValues($section = 0);

    /**
     * says if there is a section with the given name.
     */
    public function isSection($name);


    /**
     * return the list of section names.
     *
     * @return array
     */
    public function getSectionList();
}
