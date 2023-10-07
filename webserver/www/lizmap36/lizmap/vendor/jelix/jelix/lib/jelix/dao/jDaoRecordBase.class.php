<?php
/**
 * @package     jelix
 * @subpackage  dao
 *
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud, Olivier Demah, Sid-Ali Djenadi
 *
 * @copyright   2005-2018 Laurent Jouanneau
 * @copyright   2007 Loic Mathau, 2012 Sid-Ali Djenadid
 * @copyright   2010 Olivier Demah
 *
 * @see        http://www.jelix.org
 * @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Base class for all record classes generated by the dao compiler.
 *
 * @package  jelix
 * @subpackage dao
 */
abstract class jDaoRecordBase
{
    const ERROR_REQUIRED = 1;
    const ERROR_BAD_TYPE = 2;
    const ERROR_BAD_FORMAT = 3;
    const ERROR_MAXLENGTH = 4;
    const ERROR_MINLENGTH = 5;

    protected $__dao_profile = '';

    /**
     * @return string the dao selector
     */
    abstract public function getSelector();

    /**
     * @return string the jDb profile used to retrieve the record
     *
     * @since 1.6.19
     */
    public function getDbProfile()
    {
        return $this->__dao_profile;
    }

    /**
     * Sets the jDb profile used to retrieve the record.
     *
     * Do not use. Used only by jDao.
     *
     * @param string $profile
     */
    public function setDbProfile($profile)
    {
        $this->__dao_profile = $profile;
    }

    /**
     * @return array informations on all properties
     *
     * @see jDaoFactoryBase::getProperties()
     */
    abstract public function getProperties();

    /**
     * @return string[] list of properties name which contains primary keys
     *
     * @see jDaoFactoryBase::getPrimaryKeyNames()
     * @since 1.0b3
     */
    abstract public function getPrimaryKeyNames();

    /**
     * check values in the properties of the record, according on the dao definition.
     *
     * @return array|false list of errors or false if ok
     */
    public function check()
    {
        $errors = array();
        foreach ($this->getProperties() as $prop => $infos) {
            $value = $this->{$prop};

            // test required
            if ($infos['required'] && $value === null) {
                $errors[$prop][] = self::ERROR_REQUIRED;

                continue;
            }

            switch ($infos['datatype']) {
              case 'varchar':
              case 'string':
                if (!is_string($value) && $value !== null) {
                    $errors[$prop][] = self::ERROR_BAD_TYPE;

                    break;
                }
                // test regexp
                if ($infos['regExp'] !== null && preg_match($infos['regExp'], (string) $value) === 0) {
                    $errors[$prop][] = self::ERROR_BAD_FORMAT;

                    break;
                }

                //  test maxlength et minlength
                $len = iconv_strlen((string) $value, jApp::config()->charset);
                if ($infos['maxlength'] !== null && $len > intval($infos['maxlength'])) {
                    $errors[$prop][] = self::ERROR_MAXLENGTH;
                }

                if ($infos['minlength'] !== null && $len < intval($infos['minlength'])) {
                    $errors[$prop][] = self::ERROR_MINLENGTH;
                }

                break;

            case 'int':
            case 'integer':
            case 'numeric':
            case 'double':
            case 'float':
                if ($value !== null && !is_numeric($value)) {
                    $errors[$prop][] = self::ERROR_BAD_TYPE;
                }

                break;

            case 'datetime':
                if (!preg_match('/^(\d{4}-(((0[1,3-9]|1[0-2])-([012][0-9]|3[01]))|((02-([01][0-9]|2[0-9])))) (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])?$/', (string) $value)) {
                    $errors[$prop][] = self::ERROR_BAD_FORMAT;
                }

                break;

            case 'time':
                if (!preg_match('/^((([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])?$/', (string) $value)) {
                    $errors[$prop][] = self::ERROR_BAD_FORMAT;
                }

                break;

            case 'varchardate':
            case 'date':
                if (!preg_match('/^(\d{4}-(((0[1,3-9]|1[0-2])-([012][0-9]|3[01]))|((02-([01][0-9]|2[0-9])))))?$/', (string) $value)) {
                    $errors[$prop][] = self::ERROR_BAD_FORMAT;
                }

                break;
            }
        }

        return count($errors) ? $errors : false;
    }

    /**
     * set values on the properties which correspond to the primary
     * key of the record
     * This method accept a single or many values as parameter.
     */
    public function setPk()
    {
        $args = func_get_args();
        if (count($args) == 1 && is_array($args[0])) {
            $args = $args[0];
        }
        $pkf = $this->getPrimaryKeyNames();

        if (count($args) == 0 || count($args) != count($pkf)) {
            throw new jException('jelix~dao.error.keys.missing');
        }

        foreach ($pkf as $k => $prop) {
            $this->{$prop} = $args[$k];
        }

        return true;
    }

    /**
     * return the value of fields corresponding to the primary key.
     *
     * @return mixed the value or an array of values if there is several  pk
     *
     * @since 1.0b3
     */
    public function getPk()
    {
        $pkf = $this->getPrimaryKeyNames();
        if (count($pkf) == 1) {
            return $this->{$pkf[0]};
        }
        $list = array();
        foreach ($pkf as $k => $prop) {
            $list[] = $this->{$prop};
        }

        return $list;
    }

    /**
     * save the record.
     *
     * @return int 1 if success (the number of affected rows). False if the query has failed.
     *
     * @since 1.4
     */
    public function save()
    {
        $dao = jDao::get($this->getSelector(), $this->getDbProfile());
        $pkFields = $this->getPrimaryKeyNames();

        if ($this->{$pkFields[0]} == null) {
            return $dao->insert($this);
        }

        return $dao->update($this);
    }
}