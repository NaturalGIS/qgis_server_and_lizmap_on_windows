<?php
/**
 * @package     jelix
 * @subpackage  installer
 *
 * @author      Laurent Jouanneau
 * @copyright   2009-2023 Laurent Jouanneau
 *
 * @see        http://jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * container for entry points properties.
 *
 * Object for legacy installers
 *
 * @deprecated
 */
class jInstallerEntryPoint
{
    /**
     * @var StdObj configuration parameters. compiled content of config files
     *             result of the merge of entry point config, liveconfig.ini.php, localconfig.ini.php,
     *             mainconfig.ini.php and defaultconfig.ini.php
     */
    public $config;

    /**
     * @var string the filename of the configuration file dedicated to the entry point
     *             ex: <apppath>/app/system/index/config.ini.php
     */
    public $configFile;

    /**
     * combination between mainconfig.ini.php (master) and entrypoint config (overrider).
     *
     * @var jIniMultiFilesModifier
     *
     * @deprecated
     */
    public $configIni;

    /**
     * combination between mainconfig.ini.php, localconfig.ini.php (master)
     * and entrypoint config (overrider).
     *
     * @var \Jelix\IniFile\MultiIniModifier
     *
     * @deprecated as public property
     */
    public $localConfigIni;

    /**
     * liveconfig.ini.php.
     *
     * @var \Jelix\IniFile\IniModifier
     */
    public $liveConfigIni;

    /**
     * entrypoint config of app/system/.
     *
     * @var \Jelix\IniFile\IniModifier
     */
    protected $epConfigIni;

    /**
     * @var bool true if the script corresponding to the configuration
     *           is a script for CLI
     */
    public $isCliScript;

    /**
     * @var string the url path of the entry point
     */
    public $scriptName;

    /**
     * @var string the filename of the entry point
     */
    public $file;

    /**
     * @var string the type of entry point
     */
    public $type;

    /**
     * Creates a jInstallerEntryPoint object from a new
     * \Jelix\Installer\EntryPoint object.
     *
     * @param \Jelix\Installer\EntryPoint  $entryPoint
     * @param \Jelix\Installer\GlobalSetup $globalSetup
     */
    public function __construct(
        Jelix\Installer\EntryPoint $entryPoint,
        Jelix\Installer\GlobalSetup $globalSetup
    ) {
        $this->type = $entryPoint->getType();
        $this->isCliScript = $entryPoint->isCliScript();
        $this->configFile = $entryPoint->getConfigFileName();
        $this->scriptName = $entryPoint->getScriptName();
        $this->file = $entryPoint->getFileName();
        $this->config = $entryPoint->getConfigObj();
        $this->setConfiguration($entryPoint, $globalSetup);
    }

    public function setConfiguration(
        Jelix\Installer\EntryPoint $entryPoint,
        Jelix\Installer\GlobalSetup $globalSetup)
    {
        $this->epConfigIni = $entryPoint->getSingleConfigIni();

        $mainConfig = new \Jelix\IniFile\MultiIniModifier(
            $globalSetup->getDefaultConfigIni(),
            $globalSetup->getMainConfigIni()
        );

        $this->configIni = new \Jelix\IniFile\MultiIniModifier(
            $mainConfig,
            $this->epConfigIni
        );

        $localConfig = new \Jelix\IniFile\MultiIniModifier(
            $mainConfig,
            $globalSetup->getLocalConfigIni()
        );

        $this->localConfigIni = new \Jelix\IniFile\MultiIniModifier(
            $localConfig,
            $this->epConfigIni
        );

        $this->liveConfigIni = $globalSetup->getLiveConfigIni();
    }

    /**
     * @return string the entry point id (name of the entrypoint without .php)
     */
    public function getEpId()
    {
        return $this->config->urlengine['urlScriptId'];
    }

    /**
     * @return array the list of modules and their path, as stored in the
     *               compiled configuration file
     */
    public function getModulesList()
    {
        return $this->config->_allModulesPathList;
    }

    /**
     * @param mixed $moduleName
     *
     * @return \Jelix\Installer\ModuleStatus information about a specific module used
     *                                       by the entry point
     */
    public function getModule($moduleName)
    {
        $enabledLocally = $this->localConfigIni->getMaster()->getOverrider()->getValue($moduleName.'.enabled', 'modules');
        $enabledGlobally = $this->localConfigIni->getMaster()->getMaster()->getValue($moduleName.'.enabled', 'modules');

        if ($enabledLocally === null && $enabledGlobally === null) {
            // module not installed yet
            $isNativeModule = false;
        }
        else if ($enabledLocally) {
            $isNativeModule = false;
        }
        else {
            $isNativeModule = ($enabledGlobally === true);
        }

        return new \Jelix\Installer\ModuleStatus(
            $moduleName,
            $this->config->_allModulesPathList[$moduleName],
            $this->config->modules,
            $isNativeModule
        );
    }

    /**
     * the static entry point config alone (in app/system).
     *
     * @return \Jelix\IniFile\IniModifier
     *
     * @since 1.6.8
     */
    public function getEpConfigIni()
    {
        return $this->epConfigIni;
    }

    /**
     * @return string the config file name of the entry point
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * @return object the config content of the entry point, as seen when
     *                calling jApp::config()
     */
    public function getConfigObj()
    {
        return $this->config;
    }

    public function setConfigObj($config)
    {
        $this->config = $config;
    }

    /**
     * Give only the content of mainconfig.ini.php.
     *
     * @return jIniFileModifier
     */
    public function getSingleMainConfigIni()
    {
        return $this->localConfigIni->getMaster()->getMaster();
    }

    /**
     * Give only the content of localconfig.ini.php.
     *
     * @return jIniFileModifier
     */
    public function getSingleLocalConfigIni()
    {
        return $this->localConfigIni->getMaster()->getOverrider();
    }
}
