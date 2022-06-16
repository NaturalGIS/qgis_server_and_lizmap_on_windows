<?php

namespace Jelix\ComposerPlugin;


class JelixPackageParameters {

    protected $modulesDirs = array();

    protected $pluginsDirs = array();

    protected $singleModuleDirs = array();

    protected $packageName = '';

    /**
     * access to setup for modules that are in vendor modules
     *
     * Only for composer.json of an application
     *
     * @var array 'package_name'=>array('module_name'=>array("__global"=> 1, "index" => 2,...))
     */
    protected $packageModulesAccess = array();

    /**
     * access to setup for modules for each known applications
     *
     * Only for composer.json of packages of modules
     * @var array 'application_name'=>array('module_name'=>array("__global"=> 1, "index" => 2,...))
     */
    protected $appModulesAccess = array();

    protected $isAppPackage = false;

    function __construct($packageName, $isAppPackage)
    {
        $this->packageName = $packageName;
        $this->isAppPackage = $isAppPackage;
    }

    function getPackageName()
    {
        return $this->packageName;
    }

    function isApp()
    {
        return $this->isAppPackage;
    }

    function setModulesDirs($list)
    {
        $this->modulesDirs = $list;
    }

    function setPluginsDirs($list)
    {
        $this->pluginsDirs = $list;
    }

    function setSingleModuleDirs($list)
    {
        $this->singleModuleDirs = $list;
    }

    /**
     * set module access by applications
     *
     * @param  array  $list 'application_name'=>array('module_name'=>array("__global"=> 1, "index" => 2,...))
     */
    function setAppModulesAccess(array $list)
    {
        if (!$this->isAppPackage) {
            $this->appModulesAccess = $list;
        }
    }

    /**
     * set module access by package
     *
     * @param  array  $list 'package_name'=>array('module_name'=>array("__global"=> 1, "index" => 2,...))
     */
    function setPackageModulesAccess(array $list)
    {
        if ($this->isAppPackage) {
            $this->packageModulesAccess = $list;
        }
    }

    function getPackageModulesAccess()
    {
        return $this->packageModulesAccess;
    }

    function getAppModulesAccess()
    {
        return $this->appModulesAccess;
    }

    /**
     * @param string $packageName the id of the package
     *
     * @return JelixModuleAccess[]
     */
    function getModulesAccessForPackage($packageName)
    {
        if (isset($this->packageModulesAccess[$packageName])) {
            $list = array();
            foreach($this->packageModulesAccess[$packageName] as $module => $access) {
                $list[$module] = new JelixModuleAccess($module, $access);
            }
            return $list;
        }
        return array();
    }

    /**
     * @param string $composerAppName the id of the app in composer.json
     * @param string $jelixAppName the Jelix id of the app (from project.xml)
     *
     * @return JelixModuleAccess[]
     */
    function getModulesAccessForApp($composerAppName, $jelixAppName)
    {
        $moduleAccess = array();
        if (isset($this->appModulesAccess['__any_app'])) {
            $moduleAccess = $this->appModulesAccess['__any_app'];
        }

        $appModulesAccess = array();
        if (isset($this->appModulesAccess[$composerAppName])) {
            $appModulesAccess = $this->appModulesAccess[$composerAppName];
        }
        else if (isset($this->appModulesAccess[$jelixAppName])) {
            $appModulesAccess = $this->appModulesAccess[$jelixAppName];
        }

        foreach($appModulesAccess as $module => $access) {
            if (isset($moduleAccess[$module])) {
                $moduleAccess[$module] = array_merge($moduleAccess[$module], $access);
            }
            else {
                $moduleAccess[$module] = $access;
            }
        }


        $list = array();
        foreach($moduleAccess as $module => $access) {
            $list[$module] = new JelixModuleAccess($module, $access);
        }
        return $list;
    }

    /**
     * list of path to modules directories
     *
     * @return string[] list of path
     */
    function getModulesDirs()
    {
        return $this->modulesDirs;
    }

    /**
     * list of path to plugins directories
     *
     * @return string[] list of path
     */
    function getPluginsDirs()
    {
        return $this->pluginsDirs;
    }

    /**
     * list of path to single module directories
     *
     * @return string[] list of path
     */
    function getSingleModuleDirs()
    {
        return $this->singleModuleDirs;
    }

    function addModulesDir($path)
    {
        $this->modulesDirs[] = $path;
    }

    function addPluginsDir($path)
    {
        $this->pluginsDirs[] = $path;
    }

    function addSingleModuleDir($path)
    {
        $this->singleModuleDirs[] = $path;
    }
}
