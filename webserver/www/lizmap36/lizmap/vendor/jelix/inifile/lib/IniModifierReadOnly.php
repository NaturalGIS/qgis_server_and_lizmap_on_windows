<?php

/**
 * @author     Laurent Jouanneau
 * @copyright  2018 Laurent Jouanneau
 *
 * @link       https://jelix.org
 * @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Jelix\IniFile;

/**
 * utility class that decorate any IniModifierInterface/IniReaderInterface object
 * as an IniReaderInterface only interface.
 */
class IniModifierReadOnly implements IniReaderInterface
{

    /**
     * @var IniReaderInterface
     */
    protected $originalIni;

    /**
     *
     *
     * @param IniReaderInterface $iniReader
     */
    public function __construct(IniReaderInterface $iniReader)
    {
        $this->originalIni = $iniReader;
    }

    public function isEmpty()
    {
        return $this->originalIni->isEmpty();
    }

    /**
     * @return string the file name
     */
    public function getFileName()
    {
        return $this->originalIni->getFileName();
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
        return $this->originalIni->getValue($name, $section, $key);
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
        return $this->originalIni->getValues($section);
    }

    /**
     * says if there is a section with the given name.
     */
    public function isSection($name)
    {
        return $this->originalIni->isSection($name);
    }

    /**
     * return the list of section names.
     *
     * @return array
     */
    public function getSectionList()
    {
        return $this->originalIni->getSectionList();
    }
}
