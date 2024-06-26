<?php
/**
 * @package     jelix
 * @subpackage  kvdb_plugin
 *
 * @author      Yannick Le Guédart
 * @contributor Laurent Jouanneau
 *
 * @copyright   2009 Yannick Le Guédart, 2010 Laurent Jouanneau
 *
 * @see     http://jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * @deprecated TO REMOVE
 */
class file2KVDriver extends jKVDriver
{
    protected $dir;

    /**
     * "Connects" to the fileServer.
     *
     * @return fileServer object
     */
    protected function _connect()
    {
        return new fileServer(jApp::tempPath('filekv'));
    }

    protected function _disconnect()
    {
    }

    public function get($key)
    {
        return $this->_connection->get($key);
    }

    public function set($key, $value)
    {
        return $this->_connection->set(
            $key,
            $value
        );
    }

    public function insert($key, $value)
    {
        $val = $this->_connection->get($key);
        if ($val !== false) {
            return false;
        }

        return $this->_connection->set(
            $key,
            $value
        );
    }

    public function replace($key, $value)
    {
        $val = $this->_connection->get($key);
        if ($val === false) {
            return false;
        }

        return $this->_connection->set(
            $key,
            $value
        );
    }

    public function delete($key)
    {
        return $this->_connection->delete($key);
    }

    public function flush()
    {
        return $this->_connection->flush();
    }

    public function append($key, $value)
    {
        $val = $this->_connection->get($key);
        if ($val === false) {
            return false;
        }

        $val .= $value;

        if ($this->_connection->set(
            $key,
            $val
        )) {
            return $val;
        }

        return false;
    }

    public function prepend($key, $value)
    {
        $val = $this->_connection->get($key);
        if ($val === false) {
            return false;
        }

        $val = $value.$val;

        if ($this->_connection->set(
            $key,
            $val
        )) {
            return $val;
        }

        return false;
    }

    public function increment($key, $incr = 1)
    {
        $val = $this->_connection->get($key);
        if ($val === false || !is_numeric($val)) {
            return false;
        }

        $val += $incr;

        if ($this->_connection->set(
            $key,
            $val
        )) {
            return $val;
        }

        return false;
    }

    public function decrement($key, $decr = 1)
    {
        $val = $this->_connection->get($key);
        if ($val === false || !is_numeric($val)) {
            return false;
        }

        $val -= $decr;

        if ($this->_connection->set(
            $key,
            $val
        )) {
            return $val;
        }

        return false;
    }
}

class fileServer
{
    protected $dir;

    public function __construct($directory)
    {
        $this->dir = $directory;
        // Create temp kvFile directory if necessary

        if (!file_exists($this->dir)) {
            jFile::createDir($this->dir);
        }
    }

    /**
     * set.
     *
     * @param string $key   a key (unique name) to identify the cached info
     * @param mixed  $value the value to cache
     * @param int    $ttl   how many seconds will the info be cached
     *
     * @return bool whether the action was successful or not
     */
    public function set($key, $value, $ttl = 0)
    {
        $r = false;

        if ($fl = @fopen($this->dir.'/.flock', 'w+')) {
            if (flock($fl, LOCK_EX)) {
                // mutex zone

                $md5 = md5($key);
                $subdir = $md5[0].$md5[1];

                if (!file_exists($this->dir.'/'.$subdir)) {
                    jFile::createDir($this->dir.'/'.$subdir);
                }

                // write data to cache
                $fn = $this->dir.'/'.$subdir.'/'.$md5;
                if ($f = @gzopen($fn.'.tmp', 'w')) {
                    // write temporary file
                    fputs($f, base64_encode(serialize($value)));
                    fclose($f);

                    // change time of the file to the expiry time
                    @touch("{$fn}.tmp", time() + $ttl);

                    // rename the temporary file
                    $r = @rename("{$fn}.tmp", $fn);

                    chmod($fn, jApp::config()->chmodFile);
                }

                // end of mutex zone
                flock($fl, LOCK_UN);
            }
        }

        return $r;
    }

    /**
     * get.
     *
     * @param string $key the key (unique name) that identify the cached info
     *
     * @return false|mixed false if the cached info does not exist or has expired
     *                     or the data if the info exists and is valid
     */
    public function get($key)
    {
        $r = false;

        // the name of the file
        $md5 = md5($key);
        $subdir = $md5[0].$md5[1];

        $fn = $this->dir.'/'.$subdir.'/'.$md5;

        // file does not exists
        if (!file_exists($fn)) {
            return false;
        }

        //  data has expired => delete file and return false
        if (@filemtime($fn) < time()) {
            @unlink($fn);

            return false;
        }

        // date is valid
        if ($f = @gzopen($fn, 'rb')) {
            $r = '';

            while ($read = fread($f, 1024)) {
                $r .= $read;
            }

            fclose($f);
        }

        // return cached info
        return @unserialize(base64_decode($r));
    }

    /**
     * delete.
     *
     * @param string $key a key (unique name) to identify the cached info
     *
     * @return bool whether the action was successful or not
     */
    public function delete($key)
    {
        // the name of the file
        $md5 = md5($key);
        $subdir = $md5[0].$md5[1];

        $fn = $this->dir.'/'.$subdir.'/'.$md5;

        return @unlink($fn);
    }

    /**
     * flush.
     *
     * @return bool whether the action was successful or not
     */
    public function flush()
    {
        return @unlink($this->dir);
    }
}
