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

/**
 * Main class of the template engine.
 */
class Castor extends CastorCore
{
    /**
     * @var Config
     */
    protected $config = null;

    /**
     *
     * @param  Config  $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        parent::__construct();
    }

    /**
     * include the compiled template file and call one of the generated function.
     *
     * @param string $tpl        template selector
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     *
     * @return string the suffix name of the function to call
     * @throws Exception
     */
    protected function getTemplate($tpl, $outputType = '', $trusted = true)
    {
        $tpl = $this->config->templatePath.$tpl;
        if ($outputType == '') {
            $outputType = 'html';
        }

        $cachefile = dirname($this->_templateName).'/';
        if ($cachefile == './') {
            $cachefile = '';
        }

        if ($this->config->cachePath == '/' || $this->config->cachePath == '') {
            throw new Exception('cache path is invalid ! its value is: "'.$this->config->cachePath.'".');
        }

        $cachefile = $this->config->cachePath.$cachefile . $outputType . ($trusted ? '_t' : '') . '_' . basename($tpl);

        $mustCompile = $this->config->compilationForce || !file_exists($cachefile);
        if (!$mustCompile) {
            if (filemtime($tpl) > filemtime($cachefile)) {
                $mustCompile = true;
            }
        }

        if ($mustCompile) {
            $compiler = $this->getCompiler();
            $compiler->compile($this->_templateName,
                               $tpl, $outputType, $trusted,
                               $this->userModifiers, $this->userFunctions);
        }
        require_once $cachefile;

        return md5($tpl.'_' . $outputType . ($trusted ? '_t' : ''));
    }

    public function fetch($tpl, $outputType = '', $trusted = true, $callMeta = true)
    {
        return $this->_fetch($tpl, $tpl, $outputType, $trusted, $callMeta);
    }

    protected function getCachePath()
    {
        return  $this->config->cachePath.'/virtuals/';
    }

    protected function getCompiler()
    {
        return  new Compiler($this->config);
    }

    protected function compilationNeeded($cacheFile)
    {
        return $this->config->compilationForce || !file_exists($cacheFile);
    }

    /**
     * return the current encoding.
     *
     * @return string the charset string
     */
    public function getEncoding()
    {
        return $this->config->charset;
    }

    public function getLocaleString($locale)
    {
        $getter = $this->config->localesGetter;
        if ($getter) {
            $res = call_user_func($getter, $locale);
        } else {
            $res = $locale;
        }

        return $res;
    }
}
