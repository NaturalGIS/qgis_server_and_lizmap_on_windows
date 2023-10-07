<?php

/**
 * @author      Loic Mathaud
 * @contributor Laurent Jouanneau
 *
 * @copyright   2006 Loic Mathaud
 * @copyright   2006-2020 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

class Compiler extends CompilerCore
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * Initialize some properties.
     */
    public function __construct(Config $config)
    {
        parent::__construct($config->charset);
        $this->config = $config;
    }

    /**
     * Launch the compilation of a template.
     *
     * Store the result (a php content) into a cache file inside the cache directory
     *
     * @param  string  $tplFile  the file name that contains the template
     *
     * @return bool true if ok
     * @throws \Exception
     */
    public function compile($tplName, $tplFile, $outputType, $trusted,
                             $userModifiers = array(), $userFunctions = array())
    {
        $this->_sourceFile = $tplFile;
        $this->outputType = $outputType;
        $cacheFile = $this->config->cachePath.dirname($tplName).'/'.$this->outputType.($trusted ? '_t' : '').'_'.basename($tplName);
        $this->trusted = $trusted;
        $md5 = md5($tplFile.'_'.$this->outputType.($this->trusted ? '_t' : ''));

        if (!file_exists($this->_sourceFile)) {
            $this->doError0('errors.tpl.not.found');
        }

        $this->compileString(file_get_contents($this->_sourceFile), $cacheFile,
            $userModifiers, $userFunctions, $md5);

        return true;
    }

    protected function _saveCompiledString($cacheFile, $result)
    {
        $_dirname = dirname($cacheFile).'/';

        if (!is_dir($_dirname)) {
            umask($this->config->umask);
            mkdir($_dirname, $this->config->chmodDir, true);
        } elseif (!@is_writable($_dirname)) {
            throw new \Exception(sprintf($this->config->getMessage('file.directory.notwritable'), $cacheFile, $_dirname));
        }

        // write to tmp file, then rename it to avoid
        // file locking race condition
        $_tmp_file = tempnam($_dirname, 'wrt');

        if (!($fd = @fopen($_tmp_file, 'wb'))) {
            $_tmp_file = $_dirname.'/'.uniqid('wrt');
            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                throw new \Exception(sprintf($this->config->getMessage('file.write.error'), $cacheFile, $_tmp_file));
            }
        }

        fwrite($fd, $result);
        fclose($fd);

        // Delete the file if it already exists (this is needed on Win,
        // because it cannot overwrite files with rename()
        if (substr(PHP_OS, 0, 3) == 'WIN' && file_exists($cacheFile)) {
            @unlink($cacheFile);
        }

        @rename($_tmp_file, $cacheFile);
        @chmod($cacheFile, $this->config->chmodFile);
    }

    protected function getCompiledLocaleRetriever($locale)
    {
        return '$t->getLocaleString(\''.$locale.'\')';
    }

    protected function _getPlugin($type, $name)
    {
        if (isset($this->config->pluginPathList[$this->outputType])) {
            foreach ($this->config->pluginPathList[$this->outputType] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';

                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_'.$this->outputType.'_'.$name);
                }
            }
        }
        if (isset($this->config->pluginPathList['common'])) {
            foreach ($this->config->pluginPathList['common'] as $path) {
                $foundPath = $path.$type.'.'.$name.'.php';
                if (file_exists($foundPath)) {
                    return array($foundPath, 'jtpl_'.$type.'_common_'.$name);
                }
            }
        }

        return false;
    }

    public function doError0($err)
    {
        throw new \Exception($this->config->getMessage($err, array($this->_sourceFile)));
    }

    public function doError1($err, $arg)
    {
        throw new \Exception($this->config->getMessage($err, array($arg, $this->_sourceFile)));
    }

    public function doError2($err, $arg1, $arg2)
    {
        throw new \Exception($this->config->getMessage($err, array($arg1, $arg2, $this->_sourceFile)));
    }
}
