<?php

/**
 * @author      Laurent Jouanneau
 * @contributor Dominique Papin
 *
 * @copyright   2005-2022 Laurent Jouanneau, 2007 Dominique Papin
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

/**
 * base class of the template engine.
 */
abstract class CastorCore
{
    /**
     * all assigned template variables.
     * It have a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     * See methods of CastorCore to manage template variables.
     *
     * @var array
     */
    public $_vars = array();

    /**
     * temporary template variables for plugins.
     * It have a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     *
     * @var array
     */
    public $_privateVars = array();

    /**
     * internal use
     * It have a public access only for plugins. So you mustn't use directly this property
     * except from tpl plugins.
     *
     * @var array
     */
    public $_meta = array();

    /**
     * internal use
     * list of macro
     *
     * See macro and usemacro plugins
     */
    public $_macros = array();


    public function __construct()
    {
        $this->_vars['j_datenow'] = date('Y-m-d');
        $this->_vars['j_timenow'] = date('H:i:s');
    }

    /**
     * assign a value in a template variable.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            $this->_vars = array_merge($this->_vars, $name);
        } else {
            $this->_vars[$name] = $value;
        }
    }

    /**
     * assign a value by reference in a template variable.
     *
     * @param string $name  the variable name
     * @param mixed  $value the value
     */
    public function assignByRef($name, &$value)
    {
        $this->_vars[$name] = &$value;
    }

    /**
     * concat a value in with a value of an existing template variable.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function append($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (isset($this->_vars[$key])) {
                    $this->_vars[$key] .= $val;
                } else {
                    $this->_vars[$key] = $val;
                }
            }
        } else {
            if (isset($this->_vars[$name])) {
                $this->_vars[$name] .= $value;
            } else {
                $this->_vars[$name] = $value;
            }
        }
    }

    /**
     * assign a value in a template variable, only if the template variable doesn't exist.
     *
     * @param string|array $name  the variable name, or an associative array 'name'=>'value'
     * @param mixed        $value the value (or null if $name is an array)
     */
    public function assignIfNone($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                if (!isset($this->_vars[$key])) {
                    $this->_vars[$key] = $val;
                }
            }
        } else {
            if (!isset($this->_vars[$name])) {
                $this->_vars[$name] = $value;
            }
        }
    }

    /**
     * says if a template variable exists.
     *
     * @param string $name the variable template name
     *
     * @return bool true if the variable exists
     */
    public function isAssigned($name)
    {
        return isset($this->_vars[$name]);
    }

    /**
     * return the value of a template variable.
     *
     * @param string $name the variable template name
     *
     * @return mixed the value (or null if it isn't exist)
     */
    public function get($name)
    {
        if (isset($this->_vars[$name])) {
            return $this->_vars[$name];
        }
        return null;
    }

    /**
     * Return all template variables.
     *
     * @return array
     */
    public function getTemplateVars()
    {
        return $this->_vars;
    }

    /**
     * process all meta instruction of a template.
     *
     * @param string $tpl        template selector
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     */
    public function meta($tpl, $outputType = '', $trusted = true)
    {
        if (in_array($tpl, $this->processedMeta)) {
            // we want to process meta only one time, when a template is included
            // several time in an other template, or, more important, when a template
            // is included in a recursive manner (in this case, it did cause infinite loop, see #1396).
            return $this->_meta;
        }
        $this->processedMeta[] = $tpl;
        $md = $this->getTemplate($tpl, $outputType, $trusted);

        $fct = 'template_meta_'.$md;
        $fct($this);

        return $this->_meta;
    }

    /**
     * display the generated content from the given template.
     *
     * @param string $tpl        template selector
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     */
    public function display($tpl, $outputType = '', $trusted = true)
    {
        $previousTpl = $this->_templateName;
        $this->_templateName = $tpl;
        $this->recursiveTpl[] = $tpl;
        $md = $this->getTemplate($tpl, $outputType, $trusted);

        $fct = 'template_'.$md;
        $fct($this);
        array_pop($this->recursiveTpl);
        $this->_templateName = $previousTpl;
    }

    /**
     * contains the name of the template file
     * It have a public access only for plugins. So you musn't use directly this property
     * except from tpl plugins.
     *
     * @var string
     */
    public $_templateName;

    /**
     * @var string[] list of processed included template to check infinite recursion
     */
    protected $recursiveTpl = array();

    /**
     * @var array list of already processed meta information, to not duplicate
     *            meta content
     */
    protected $processedMeta = array();

    /**
     * include the compiled template file and call one of the generated function.
     *
     * @param string $tpl        template selector
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     *
     * @return string the suffix name of the function to call
     */
    abstract protected function getTemplate($tpl, $outputType = '', $trusted = true);

    /**
     * return the generated content from the given template.
     *
     * @param string $tpl        template selector
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return string the generated content
     */
    abstract public function fetch($tpl, $outputType = '', $trusted = true, $callMeta = true);

    /**
     * @param string $tpl        the template name
     * @param string $getTemplateArg
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return false|string
     */
    protected function _fetch($tpl, $getTemplateArg, $outputtype = '', $trusted = true, $callMeta = true)
    {
        ob_start();
        try {
            $previousTpl = $this->_templateName;
            $this->_templateName = $tpl;
            if ($callMeta) {
                if (in_array($tpl, $this->processedMeta)) {
                    $callMeta = false;
                } else {
                    $this->processedMeta[] = $tpl;
                }
            }
            $this->recursiveTpl[] = $tpl;

            $md = $this->getTemplate($getTemplateArg, $outputtype, $trusted);

            if ($callMeta) {
                $fct = 'template_meta_'.$md;
                $fct($this);
            }
            $fct = 'template_'.$md;
            $fct($this);
            array_pop($this->recursiveTpl);
            $this->_templateName = $previousTpl;
            $content = ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return $content;
    }

    /**
     * Return the generated content from the given string template (virtual).
     *
     * @param string $tpl        template content
     * @param string $outputType the type of output (html, text etc..)
     * @param bool   $trusted    says if the template file is trusted or not
     * @param bool   $callMeta   false if meta should not be called
     *
     * @return string the generated content
     */
    public function fetchFromString($tpl, $outputType = '', $trusted = true, $callMeta = true)
    {
        ob_start();
        try {
            $cachePath = $this->getCachePath().'virtual/';

            $previousTpl = $this->_templateName;
            $md = 'virtual_'.md5($tpl).($trusted ? '_t' : '');
            $this->_templateName = $md;

            if ($outputType == '') {
                $outputType = 'html';
            }

            $cachePath .= $outputType . '_' . $this->_templateName . '.php';

            $mustCompile = $this->compilationNeeded($cachePath);

            if ($mustCompile && !function_exists('template_'.$md)) {
                $compiler = $this->getCompiler();
                $compiler->outputType = $outputType;
                $compiler->trusted = $trusted;
                $compiler->compileString($tpl,
                                         $cachePath,
                                         $this->userModifiers,
                                         $this->userFunctions,
                                         $md);
            }
            require_once $cachePath;

            if ($callMeta) {
                $fct = 'template_meta_'.$md;
                $fct($this);
            }
            $fct = 'template_'.$md;
            $fct($this);
            $content = ob_get_clean();
            $this->_templateName = $previousTpl;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return $content;
    }

    abstract protected function getCachePath();

    abstract protected function getCompiler();

    abstract protected function compilationNeeded($cacheFile);

    protected $userModifiers = array();

    /**
     * register a user modifier. The function should accept at least a
     * string as first parameter, and should return this string
     * which can be modified.
     *
     * @param string $name         the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     */
    public function registerModifier($name, $functionName)
    {
        $this->userModifiers[$name] = $functionName;
    }

    protected $userFunctions = array();

    /**
     * register a user function. The function should accept at least a CastorCore object
     * as first parameter.
     *
     * @param string $name         the name of the modifier in a template
     * @param string $functionName the corresponding PHP function
     */
    public function registerFunction($name, $functionName)
    {
        $this->userFunctions[$name] = $functionName;
    }

    /**
     * return the current encoding.
     *
     * @return string the charset string
     */
    public function getEncoding()
    {
        return '';
    }

    /**
     * @return \Exception
     */
    public function getInternalException($messageKey, $parameters)
    {
        $msg = $this->config->getMessage($messageKey, $parameters);

        return new \Exception($msg);
    }

    /**
     * @param string $macroName the macro name
     * @param array $parametersNames parameter names for the macro
     * @param callable $func the macro itself, as a function accepting a CastorCore engine as a parameter.
     * @return void
     */
    public function declareMacro($macroName, array $parametersNames, callable $func)
    {
        $this->_macros[$macroName] = array(
            $func,
            $parametersNames
        );
    }

    public function isMacroDefined($macroName)
    {
        return isset($this->_macros[$macroName]);
    }

    /**
     * Call the given macro. Parameters are injected into the template engine as template variables, and removed
     * after the call of the macro.
     *
     * @param string $macroName the macro name to call
     * @param array $parameters parameters for the macro. This is an associative array, with variables names as keys.
     * @return void
     */
    public function callMacro($macroName, $parameters)
    {
        if (!isset($this->_macros[$macroName])) {
            return;
        }

        list($func, $paramNames) =  $this->_macros[$macroName];
        $backupVars = array();

        foreach ($paramNames as $k => $pName) {
            if (isset($this->_vars[$pName])) {
                $backupVars[$pName] = $this->_vars[$pName];
            }
            $this->_vars[$pName] = $parameters[$k];
        }

        $func($this);

        // delete or restore parameters
        foreach ($paramNames as $k => $pName) {
            if (array_key_exists($pName, $backupVars)) {
                $this->_vars[$pName] = $backupVars[$pName];
            }
            else {
                unset($this->_vars[$pName]);
            }
        }
    }

}
