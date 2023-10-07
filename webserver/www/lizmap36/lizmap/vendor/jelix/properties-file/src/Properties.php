<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2018 Laurent Jouanneau
 *
 * @link        https://jelix.org
 * @licence     MIT
 */
namespace Jelix\PropertiesFile;

/**
 * Container for properties readed from a properties file
 * @package Jelix\PropertiesFile
 */
class Properties implements \ArrayAccess
{

    /**
     * @var string[] list of key/values
     */
    protected $properties = array();

    /**
     * Properties constructor.
     * @param string[] $properties initial properties
     */
    public function __construct($properties = array())
    {
        $this->properties = $properties;
    }

    /**
     * Get a property
     *
     * @param string $key
     * @return null|string
     */
    public function get($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }
        return null;
    }

    /**
     * Return all properties
     *
     * @return string[]
     */
    public function getAllProperties()
    {
        return $this->properties;
    }

    /**
     * Gets an iterator on all properties
     *
     * @return \ArrayIterator the iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }

    /**
     * Set a property
     *
     * @param string $key
     * @param string $value
     */
    public function set($key, $value)
    {
        $this->properties[$key] = $value;
    }

    // --------------------------- ArrayAccess interface

    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->properties[] = $value;
        } else {
            $this->properties[$key] = $value;
        }
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($key)
    {
        return isset($this->properties[$key]);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->properties[$key]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }
}
