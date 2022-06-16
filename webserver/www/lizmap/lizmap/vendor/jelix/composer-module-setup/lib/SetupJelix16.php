<?php

namespace Jelix\ComposerPlugin;
use Composer\Util\Filesystem;
use Jelix\ComposerPlugin\Ini\IniModifier;

/**
 *  Setup configuration for a Jelix 1.6 application
 */
class SetupJelix16 {

    /**
     * @var JelixParameters
     */
    protected $parameters;

    /**
     * @var Filesystem
     */
    protected $fs;


    /**
     * list of entrypoint configuration
     *
     * @var IniModifier[]
     */
    protected $entryPoints = array();

    /**
     * @var string
     */
    protected $appId = '';


    /**
     * @var DebugLogger|null
     */
    protected $logger;

    /**
     *
     * @param  JelixParameters  $parameters
     * @param  DebugLogger|null  $logger
     */
    function __construct(JelixParameters $parameters, $logger = null) {
        $this->parameters = $parameters;
        $this->fs = new Filesystem();
        $this->logger = $logger;
    }

    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->log($message);
        }
    }

    /**
     * Update the configuration of the application according to informations
     * readed from all composer.json
     *
     * Warning: during upgrade of composer-module-setup, it seems Composer load some classes
     * of the previous version (here ModuleSetup + JelixParameters), and load
     * other classes (here SetupJelix16) after the upgrade. so API is not the one we expected.
     * so we should check if the new methods of JelixParameters are there before
     * using them.
     *
     * @throws \Exception
     */
    function setup() {

        $this->log("--- Setup jelix16 starts");
        $allModulesDir = $this->parameters->getAllModulesDirs();
        $allPluginsDir = $this->parameters->getAllPluginsDirs();
        $allModules = $this->parameters->getAllSingleModuleDirs();

        $appDir = $this->parameters->getAppDir();
        if (!$appDir) {
            throw new \Exception("No application directory is set in JelixParameters");
        }

        $this->readProjectXml();
        $ini = $this->loadLocalConfigFile();
        $configDir = $this->parameters->getVarConfigDir();

        $vendorPath = $this->getFinalPath('./');

        // retrieve the current modulesPath value
        $modulesPath = $this->getCurrentModulesPath($configDir, $ini, $vendorPath);
        if (count($allModulesDir)) {
            // add all declared modules directories
            foreach($allModulesDir as $path) {
                $modulesPath[] = $this->getFinalPath($path);
            }
        }
        $modulesPath =  implode(',', array_unique($modulesPath));
        if ($ini->getValue('modulesPath') != $modulesPath) {
            $ini->setValue('modulesPath', $modulesPath);
            $this->log('New modulesPath: '.$modulesPath);
        }

        // retrieve the current pluginsPath value
        $pluginsPath = $this->getCurrentPluginsPath($configDir, $ini, $vendorPath);
        if (count($allPluginsDir)) {
            // add all declared plugins directories
            foreach($allPluginsDir as $path) {
                $pluginsPath[] = $this->getFinalPath($path);
            }
        }
        $pluginsPath = implode(',', array_unique($pluginsPath));
        if ($ini->getValue('pluginsPath') != $pluginsPath) {
            $ini->setValue('pluginsPath', $pluginsPath);
            $this->log('New pluginsPath: '.$pluginsPath);
        }

        $modulePathToRemove = array();
        foreach($ini->getValues('modules') as $key => $val) {
            if (preg_match("/\\.path$/", $key) && strpos($val, $vendorPath) === 0) {
                $modulePathToRemove[$key] = $val;
            }
        }

        if (count($allModules)) {
            // declare path of single modules
            foreach($allModules as $path) {
                $path = $this->fs->normalizePath($path);
                $moduleName = basename($path);

                $path = $this->getFinalPath($path);
                $this->log("setup path to module $moduleName");
                if ($ini->getValue($moduleName.'.path', 'modules') != $path) {
                    $ini->setValue($moduleName.'.path', $path, 'modules');
                }

                if (isset($modulePathToRemove[$moduleName.'.path'])) {
                    unset($modulePathToRemove[$moduleName.'.path']);
                }
            }
        }

        // erase all "<module>.path" keys of modules that are not inside a package anymore
        foreach ($modulePathToRemove as $key => $path) {
            $ini->removeValue($key, 'modules');
            $this->log("remove path to module $key");
        }

        $this->setupModuleAccess($ini);

        $ini->save();
        foreach($this->entryPoints as $epIni) {
            $epIni->save();
        }
        $this->log("Setup jelix16 ends");
    }

    /**
     * @return IniModifier
     * @throws \Exception
     */
    protected function loadLocalConfigFile()
    {
        $configDir = $this->parameters->getVarConfigDir();

        // open the configuration file
        if (method_exists($this->parameters, 'getConfigFileName')) {
            $iniFileName = $this->parameters->getConfigFileName();
        }
        else {
            $iniFileName = 'localconfig.ini.php';
        }
        if (!$iniFileName) {
            $iniFileName = 'localconfig.ini.php';
        }
        $iniFileName= $configDir.$iniFileName;
        if (!file_exists($iniFileName)) {
            if (!file_exists($configDir)) {
                throw new \Exception('Configuration directory "'.$configDir.'" for the app does not exist');
            }
            file_put_contents($iniFileName, "<"."?php\n;die(''); ?".">\n\n");
        }
        $ini = new IniModifier($iniFileName);
        return $ini;
    }

    /**
     * @return IniModifier
     * @throws \Exception
     */
    protected function loadMainConfigFile()
    {
        $configDir = $this->parameters->getVarConfigDir();
        $iniFileName= $configDir.'mainconfig.ini.php';
        if (!file_exists($iniFileName)) {
            throw new \Exception('mainconfig.ini.php does not exist');
        }
        $ini = new IniModifier($iniFileName);
        return $ini;
    }

    protected function getCurrentModulesPath($configDir, $localIni, $vendorPath) {

        $modulesPath = $localIni->getValue('modulesPath');
        if ($modulesPath == '') {
            $mainConfigIni = new IniModifier($configDir.'mainconfig.ini.php');
            $modulesPath = $mainConfigIni->getValue('modulesPath');
            if ($modulesPath == '') {
                $modulesPath = 'lib:jelix-modules/,app:modules/';
            }
        }
        $pathList = preg_split('/ *, */', $modulesPath);
        return $this->removeVendorPath($pathList, $vendorPath);
    }

    protected function getCurrentPluginsPath($configDir, $localIni, $vendorPath) {

        $pluginsPath = $localIni->getValue('pluginsPath');
        if ($pluginsPath == '') {
            $mainConfigIni = new IniModifier($configDir.'mainconfig.ini.php');
            $pluginsPath = $mainConfigIni->getValue('pluginsPath');
            if ($pluginsPath == '') {
                $pluginsPath = 'app:plugins/';
            }
        }
        $pathList = preg_split('/ *, */', $pluginsPath);
        return $this->removeVendorPath($pathList, $vendorPath);
    }

    /**
     * Remove all path that are into the vendor directory, to be sure there will
     * not have anymore path from packages that are not existing anymore.
     *
     * @param string[] $pathList
     */
    protected function removeVendorPath($pathList, $vendorPath) {
        $list = [];

        foreach ($pathList as $path) {
            if (strpos($path, $vendorPath) !== 0) {
                $list[] = rtrim($path, '/');
            }
        }
        return $list;
    }

    protected function getFinalPath($path) {
        $appDir = $this->parameters->getAppDir();
        $vendorDir = $this->parameters->getVendorDir();
        $path = $this->fs->findShortestPath($appDir, $vendorDir.$path, true);
        if ($this->fs->isAbsolutePath($path)) {
            return $path;
        }
        if (substr($path, 0,2) == './') {
            $path = substr($path, 2);
        }
        return 'app:'.$path;
    }

    protected function readProjectXml()
    {
        $appDir = $this->parameters->getAppDir();
        $configDir = $this->parameters->getVarConfigDir();

        $this->entryPoints = array();
        $xml = simplexml_load_file($appDir.'/project.xml');
        // read all entry points data
        foreach ($xml->entrypoints->entry as $entrypoint) {
            $file                     = (string)$entrypoint['file'];
            $configFile               = (string)$entrypoint['config'];
            $file                     = str_replace('.php', '', $file);
            $this->entryPoints[$file] = new IniModifier(
                $configDir . $configFile
            );
        }
        $this->appId = (string) $xml->info['id'];
    }

    protected function setupModuleAccess(IniModifier $localIni)
    {
        if (!method_exists($this->parameters, 'getPackages')) {
            // the method does not exists during update of an old version of composer-module-setup
            return;
        }

        $this->log("starts setup of modules access");
        $appPackage = $this->parameters->getApplicationPackage();
        $modulesUrlEngine = array();
        foreach($this->parameters->getPackages() as $packageName => $package)
        {
            if (!method_exists($package, 'isApp')) {
                // the method does not exists during update of an old version of composer-module-setup
                continue;
            }
            if ($package->isApp()) {
                continue;
            }
            // let's see if the application defines configuration of entrypoint
            // for the package
            $modulesAccess = $appPackage->getModulesAccessForPackage($packageName);
            if (count($modulesAccess) == 0) {
                // no, so let's retrieve entrypoint configuration from the
                // package
                $modulesAccess = $package->getModulesAccessForApp($appPackage->getPackageName(), $this->appId);
            }
            if (count($modulesAccess) == 0) {
                // no entrypoint configuration for the package, let's ignore it
                $this->log("package $packageName: no setup access because no access definition");
                continue;
            }

            foreach ($modulesAccess as $module=>$access) {

                $accessList = $access->getAccess();
                $globalAccessValue = 0;
                if (isset($accessList['__global'])) {
                    $globalAccessValue = $accessList['__global'];
                    if ($globalAccessValue == 2) {
                        $modulesUrlEngine[$module] = '__default_index';
                    }
                }

                foreach($this->entryPoints as $epId => $ep) {
                    if (isset($accessList[$epId])) {
                        $accessValue = $accessList[$epId];
                        $ep->setValue($module.'.access', $accessValue, 'modules');
                        $this->log("module $module: set access $accessValue on $epId");
                        if ($accessValue == 2 && (!isset($modulesUrlEngine[$module]) || $modulesUrlEngine[$module] == '__default_index')) {
                            $modulesUrlEngine[$module] = $epId;
                        }
                    }
                    else {
                        $this->log("module $module: remove access on $epId");
                        $ep->removeValue($module.'.access', 'modules');
                    }
                }

                if (!isset($modulesUrlEngine[$module])) {
                    // no entry point has been selected for the url engine
                    // globalAccessValue=1 or 0
                    $modulesUrlEngine[$module] = 0;
                }
                elseif ($modulesUrlEngine[$module] == '__default_index') {
                    // module is activated globally with access=2, but not
                    // on any entrypoints. We should activate it on an entry point.
                    // first if index is free, we activate by default on index
                    if (!isset($accessList['index']) &&
                        isset($this->entryPoints['index'])
                    ) {
                        $modulesUrlEngine[$module] = 'index';
                        $this->entryPoints['index']->setValue($module.'.access', 2, 'modules');
                        $this->log("module $module: set default access 2 on index");
                        $globalAccessValue = 1;
                    }
                    else { // we activate on the first entrypoint we find.
                        $epList = array_diff_key($this->entryPoints, $accessList);
                        if (count($epList) > 0) {
                            $ep = array_keys($epList) [0];
                            $modulesUrlEngine[$module] = $ep;
                            $globalAccessValue = 1;
                            $this->entryPoints[$ep]->setValue($module.'.access', 2, 'modules');
                            $this->log("module $module: set default access 2 on $ep");
                        }
                    }
                }
                $this->log("module $module: set global access $globalAccessValue");
                $localIni->setValue($module.'.access', $globalAccessValue, 'modules');
            }
        }

        foreach($this->parameters->getRemovedPackages() as $packageName => $package)
        {
            if (!method_exists($package, 'isApp')) {
                continue;
            }
            if ($package->isApp()) {
                continue;
            }
            // let's see if the application defines configuration of entrypoint
            // for the package
            $modulesAccess = $appPackage->getModulesAccessForPackage($packageName);
            if (count($modulesAccess) == 0) {
                // no, so let's retrieve entrypoint configuration from the
                // package
                $modulesAccess = $package->getModulesAccessForApp($appPackage->getPackageName(), $this->appId);
            }
            if (count($modulesAccess) == 0) {
                // no entrypoint configuration for the package, let's ignore it
                $this->log("package $packageName: no remove access because no access definition");
                continue;
            }

            foreach ($modulesAccess as $module=>$access) {
                $this->log("module $module: remove access");
                $modulesUrlEngine[$module] = 0;
                $localIni->removeValue($module.'.access', 'modules');
                foreach ($this->entryPoints as $ep=>$ini) {
                    $ini->removeValue($module.'.access', 'modules');
                }
            }
        }

        $this->updateUrlEngineConfig($localIni, $modulesUrlEngine);

        $this->log("ends of setup of modules access");
    }


    /**
     * @param $localConfig
     * @param array $modulesMainEp module=>main entry point or 0 if deleted
     *
     * @throws \Exception
     */
    protected function updateUrlEngineConfig($localConfig, $modulesMainEp)
    {
        $epUrl = $localConfig->getValues('simple_urlengine_entrypoints');
        if (!$epUrl) {
            $mainConfigIni = $this->loadMainConfigFile();
            $epUrl = $mainConfigIni->getValues('simple_urlengine_entrypoints');
        }
        foreach($epUrl as $ep => $listModules) {
            $epUrl[$ep] = preg_split("/[\s,]+/", $listModules);
            $epUrl[$ep]= array_diff($epUrl[$ep], array('')); // cleanup
        }

        $toRemove = array();

        foreach ($modulesMainEp as $module => $mainEp)
        {
            $pattern = $module.'~*@classic';

            foreach($epUrl as $ep => $listModules) {
                if ($mainEp && $ep == $mainEp) {
                    $this->log("urlengine $ep: add $pattern");
                    $epUrl[$ep][] = $pattern;
                }
                else {
                    $this->log("urlengine $ep: remove $pattern");
                    $toRemove[$ep][] = $pattern;
                }
            }
        }

        foreach ($toRemove as $ep => $removeList) {
            if (count($removeList)) {
                $epUrl[$ep] = array_diff($epUrl[$ep], $toRemove[$ep]);
            }
        }

        foreach ($epUrl as $ep => $listModules) {
            $listModules = array_unique($listModules);
            if (array_search('@classic', $listModules) !== false) {
                $listModules = ['@classic'];
            }
            $localConfig->setValue($ep,  implode(',',$listModules), 'simple_urlengine_entrypoints');
        }
    }
}
