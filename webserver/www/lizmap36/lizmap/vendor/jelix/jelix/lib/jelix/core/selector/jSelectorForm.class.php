<?php
/**
 * see jISelector.iface.php for documentation about selectors. Here abstract class for many selectors.
 *
 * @package     jelix
 * @subpackage  core_selector
 *
 * @author      Laurent Jouanneau
 * @copyright   2005-2019 Laurent Jouanneau
 *
 * @see        http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * Form selector.
 *
 * syntax : "module~formName".
 * file : forms/formName.form.xml .
 *
 * @package    jelix
 * @subpackage core_selector
 */
class jSelectorForm extends jSelectorModule
{
    protected $type = 'form';
    protected $_where;
    protected $_dirname = 'forms/';
    protected $_suffix = '.form.xml';

    public function __construct($sel)
    {
        $this->_compiler = 'jFormsCompiler';
        $this->_compilerPath = JELIX_LIB_PATH.'forms/jFormsCompiler.class.php';

        parent::__construct($sel);
    }

    public function getClass()
    {
        return 'cForm_'.$this->module.'_Jx_'.$this->resource;
    }

    protected function _createPath()
    {
        if (!jApp::isModuleEnabled($this->module)) {
            throw new jExceptionSelector('jelix~errors.selector.module.unknown', $this->toString(true));
        }

        $resolutionInCache = jApp::config()->compilation['sourceFileResolutionInCache'];

        if ($resolutionInCache) {
            $resolutionPath = jApp::tempPath('resolved/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix);
            $resolutionCachePath = 'resolved/';
            if (file_exists($resolutionPath)) {
                $this->_path = $resolutionPath;
                $this->_where = $resolutionCachePath;

                return;
            }
            jFile::createDir(dirname($resolutionPath));
        }

        $this->findPath();

        if ($resolutionInCache) {
            symlink($this->_path, $resolutionPath);
            $this->_path = $resolutionPath;
            $this->_where = $resolutionCachePath;
        }
    }

    protected function findPath()
    {

        // we see if the forms have been redefined in var/
        $overloadedPath = jApp::varPath('overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix);
        if (is_readable($overloadedPath)) {
            $this->_path = $overloadedPath;
            $this->_where = 'var/';

            return;
        }

        // we see if the forms have been redefined in app/
        $overloadedPath = jApp::appPath('app/overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix);
        if (is_readable($overloadedPath)) {
            $this->_path = $overloadedPath;
            $this->_where = 'app/';

            return;
        }

        $this->_path = jApp::getModulePath($this->module).$this->_dirname.$this->resource.$this->_suffix;
        if (!is_readable($this->_path)) {
            throw new jExceptionSelector('jelix~errors.selector.invalid.target', array($this->toString(), $this->type));
        }
        $this->_where = 'modules/';
    }

    protected function _createCachePath()
    {
        // don't share the same cache for all the possible dirs
        // in case of overload removal
        $this->_cachePath = jApp::tempPath('compiled/'.$this->_dirname.$this->_where.$this->module.'/'.$this->resource.'_15'.$this->_cacheSuffix);
    }

    public function getCompiledBuilderFilePath($type)
    {
        return jApp::tempPath('compiled/'.$this->_dirname.$this->_where.$this->module.'/'.$this->resource.'_builder_'.$type.$this->_cacheSuffix);
    }
}
