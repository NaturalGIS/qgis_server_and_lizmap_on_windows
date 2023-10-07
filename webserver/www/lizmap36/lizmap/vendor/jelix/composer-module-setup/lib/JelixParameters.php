<?php

namespace Jelix\ComposerPlugin;
use Composer\Util\Filesystem;
use Composer\Package\PackageInterface;

class JelixParameters {

    const VERSION = 1;

    /**
     * @var JelixPackageParameters[]
     */
    protected $packagesInfos = array();

    /**
     * @var JelixPackageParameters[]
     */
    protected $removedPackagesInfos = array();

    protected $fs;

    /**
     * @var string
     */
    protected $vendorDir;

    /**
     * @var string
     */
    protected $varConfigDir;

    /**
     * @var string
     */
    protected $appDir = null;

    protected $autoconfig16 = array();

    protected $jelixTarget = '';

    /**
     * name of the configuration file that will be modified to declare
     * module paths. Only for Jelix 1.6
     * @var string $configurationFileName
     */
    protected $configurationFileName = '';

    function __construct($vendorDir)
    {
        $this->fs = new Filesystem();
        $this->vendorDir = rtrim($vendorDir, '/').'/';
    }

    function loadFromFile($fileName = '')
    {
        // for old version of ModuleSetup
        if (strpos($fileName, $this->vendorDir) === 0 ) {
            $fileName = basename($fileName);
        }
        $file = new PackagesInformationFile($this->vendorDir, $fileName);
        if ($file->exists()) {
            list (
                $this->packagesInfos,
                $this->jelixTarget
                ) = $file->load();
            return true;
        }
        return false;
    }

    function saveToFile($fileName = '')
    {
        // for old version of ModuleSetup
        if (strpos($fileName, $this->vendorDir) === 0 ) {
            $fileName = basename($fileName);
        }
        $file = new PackagesInformationFile($this->vendorDir, $fileName);

        $file->save($this->packagesInfos, $this->jelixTarget);
    }

    function getAppDir() {
        return $this->appDir;
    }

    function getVarConfigDir() {
        return $this->varConfigDir;
    }

    function getConfigFileName() {
        return $this->configurationFileName;
    }

    function getVendorDir() {
        return $this->vendorDir;
    }

    /**
     * @param string $packageName package composer name
     * @param array $extra content of the extra section of the composer.json of the package
     * @param string $packagePath path to the package
     *
     */
    function addApplicationPackage($packageName, $extra, $packagePath)
    {
        $parameters = new JelixPackageParameters($packageName, true);
        $this->packagesInfos[$packageName] = $parameters;

        if (isset($this->removedPackagesInfos[$packageName])) {
            unset($this->removedPackagesInfos[$packageName]);
        }

        if (!isset($extra['jelix'])) {
            $this->appDir = $packagePath;
            $this->appDir = rtrim($this->appDir, "/") . "/";
            $this->varConfigDir = $this->appDir . 'var/config/';

            return;
        }

        // This is the package of the application.
        // Process information from the composer.json of the application

        if (isset($extra['jelix']['app-dir'])) {
            if ($this->fs->isAbsolutePath($extra['jelix']['app-dir'])) {
                $this->appDir = $extra['jelix']['app-dir'];
            } else {
                $this->appDir = $this->fs->normalizePath($packagePath . DIRECTORY_SEPARATOR . $extra['jelix']['app-dir']);
            }
            if (!$this->appDir || !file_exists($this->appDir)) {
                throw new ReaderException("Error in composer.json of " . $packageName . ": extra/jelix/app-dir is not set or does not contain a valid path");
            }
            if (!file_exists($this->appDir . '/project.xml')) {
                throw new ReaderException("Error in composer.json of " . $packageName . ": extra/jelix/app-dir is not a path to a Jelix application");
            }

        } else {
            $this->appDir = $packagePath;
        }
        $this->appDir = rtrim($this->appDir, "/") . "/";

        $this->varConfigDir = $this->appDir . 'var/config/';

        if (isset($extra['jelix']['var-config-dir'])) {
            if ($this->fs->isAbsolutePath($extra['jelix']['var-config-dir'])) {
                $this->varConfigDir = $extra['jelix']['var-config-dir'];
            } else {
                $this->varConfigDir = $this->fs->normalizePath($packagePath . DIRECTORY_SEPARATOR . $extra['jelix']['var-config-dir']);
            }
            if (!$this->varConfigDir || !file_exists($this->varConfigDir)) {
                throw new ReaderException("Error in composer.json of " . $packageName . ": extra/jelix/var-config-dir is not set or does not contain a valid path");
            }
            $this->varConfigDir = rtrim($this->varConfigDir, "/") . "/";
        }

        if (isset($extra['jelix']['target-jelix-version']) && preg_match("/^(\\d+\\.\\d+)/", $extra['jelix']['target-jelix-version'], $v)) {
            $this->jelixTarget = $v[1];
        } else if (file_exists($this->appDir . 'app/system/mainconfig.ini.php')) {
            $this->jelixTarget = '1.7';
        } else if (file_exists($this->varConfigDir . 'mainconfig.ini.php')) {
            $this->jelixTarget = '1.6';
        }

        if (isset($extra['jelix']['config-file-16'])) {
            $this->configurationFileName = $extra['jelix']['config-file-16'];
        }

        if (isset($extra['jelix']['modules-autoconfig-access-16'])) {
            $modulesAccess = $extra['jelix']['modules-autoconfig-access-16'];
            if (is_array($modulesAccess)) {
                $cleanedModulesAccess = array();
                foreach ($extra['jelix']['modules-autoconfig-access-16'] as $package => $moduleAccess) {
                    if (!is_string($package) || !is_array($moduleAccess)) {
                        continue;
                    }
                    foreach ($moduleAccess as $module => $access) {
                        if (is_array($access)) {
                            if (!array_key_exists($package, $cleanedModulesAccess)) {
                                $cleanedModulesAccess[$package] = array();
                            }
                            $cleanedModulesAccess[$package][$module] = $access;
                        }
                    }
                }
                $parameters->setPackageModulesAccess($cleanedModulesAccess);
            }
        }

        // read information that can be in any composer.json
        $this->readModuleDirs($packageName, $packagePath, $parameters, $extra);
    }


    /**
     * @param string $packageName package composer name
     * @param array $extra content of the extra section of the composer.json of the package
     * @param string $packagePath path to the package
     */
    function addPackage($packageName, $extra, $packagePath)
    {
        $parameters = new JelixPackageParameters($packageName, false);
        $this->packagesInfos[$packageName] = $parameters;

        if (isset($this->removedPackagesInfos[$packageName])) {
            unset($this->removedPackagesInfos[$packageName]);
        }

        if (!isset($extra['jelix'])) {
            return;
        }

        // read information from the composer.json of a module package
        $this->readAutoconfigAccess($parameters, $extra);

        // read information that can be in any composer.json
        $this->readModuleDirs($packageName, $packagePath, $parameters, $extra);
    }


    protected function readModuleDirs($packageName, $packagePath, JelixPackageParameters $parameters, &$extra)
    {

        if (isset($extra['jelix']['modules-dir'])) {
            if (!is_array($extra['jelix']['modules-dir'])) {
                throw new ReaderException("Error in composer.json of ".$packageName.": extra/jelix/modules-dir is not an array");
            }
            foreach($extra['jelix']['modules-dir'] as $path) {
                $path = $packagePath.DIRECTORY_SEPARATOR.$path;
                $parameters->addModulesDir($this->fs->findShortestPath($this->vendorDir, $path, true));
            }
        }

        if (isset($extra['jelix']['modules'])) {
            if (!is_array($extra['jelix']['modules'])) {
                throw new ReaderException("Error in composer.json of " . $packageName . ": extra/jelix/modules is not an array");
            }
            foreach($extra['jelix']['modules'] as $path) {
                $path = $packagePath.DIRECTORY_SEPARATOR.$path;
                $parameters->addSingleModuleDir($this->fs->findShortestPath($this->vendorDir, $path, true));
            }
        }

        if (isset($extra['jelix']['plugins-dir'])) {
            if (!is_array($extra['jelix']['plugins-dir'])) {
                throw new ReaderException("Error in composer.json of ".$packageName.": extra/jelix/plugins-dir is not an array");
            }
            foreach($extra['jelix']['plugins-dir'] as $path) {
                $path = $packagePath.DIRECTORY_SEPARATOR.$path;
                $parameters->addPluginsDir($this->fs->findShortestPath($this->vendorDir, $path, true));
            }
        }
    }

    /**
     * read informations from the composer.json of a module package
     *
     * @param JelixPackageParameters $parameters
     * @param $extra
     * @return void
     */
    protected function readAutoconfigAccess(JelixPackageParameters $parameters, $extra)
    {
        if (isset($extra['jelix']['autoconfig-access-16'])) {
            $modulesAccess = $extra['jelix']['autoconfig-access-16'];
            if (is_array($modulesAccess)) {
                $cleanedModulesAccess = array();
                foreach($extra['jelix']['autoconfig-access-16'] as $app => $moduleAccess) {
                    if (!is_string($app) || !is_array($moduleAccess)) {
                        continue;
                    }
                    foreach($moduleAccess as $module =>$access) {
                        if (is_array($access)) {
                            if (!array_key_exists($app, $cleanedModulesAccess)) {
                                $cleanedModulesAccess[$app] = array();
                            }
                            $cleanedModulesAccess[$app][$module] = $access;
                        }
                    }
                }
                $parameters->setAppModulesAccess($cleanedModulesAccess);
            }
        }
    }

    function removePackage($packageName, $extra) {
        if(isset($this->packagesInfos[$packageName]))
        {
            unset($this->packagesInfos[$packageName]);
        }

        $parameters = new JelixPackageParameters($packageName, false);
        $this->removedPackagesInfos[$packageName] = $parameters;
        $this->readAutoconfigAccess($parameters, $extra);
    }

    function getPackageParameters($packageName) {
        if(isset($this->packagesInfos[$packageName]))
        {
            return $this->packagesInfos[$packageName];
        }
        return null;
    }

    /**
     * @return JelixPackageParameters[]
     */
    function getPackages()
    {
        return $this->packagesInfos;
    }

    /**
     * @return JelixPackageParameters[]
     */
    function getRemovedPackages()
    {
        return $this->removedPackagesInfos;
    }

    /**
     * @return JelixPackageParameters
     */
    function getApplicationPackage()
    {
        foreach($this->packagesInfos as $package)
        {
            if ($package->isApp()) {
                return $package;
            }
        }
        return null;
    }

    function getAllModulesDirs() {
        $allModulesDir = array();
        foreach( $this->packagesInfos as $packageName => $parameters) {
            $allModulesDir = array_merge($allModulesDir, $parameters->getModulesDirs());
        }
        return $allModulesDir;
    }

    function getAllPluginsDirs() {
        $allPluginsDir = array();
        foreach( $this->packagesInfos as $packageName => $parameters) {
            $allPluginsDir = array_merge($allPluginsDir, $parameters->getPluginsDirs());
        }
        return $allPluginsDir;
    }

    function getAllSingleModuleDirs() {
        $allModules = array();
        foreach( $this->packagesInfos as $packageName => $parameters) {
            $allModules = array_merge($allModules, $parameters->getSingleModuleDirs());
        }
        return $allModules;
    }

    function isJelix16()
    {
        if ($this->jelixTarget == '') {
            if ($this->getPackageParameters('jelix/jelix') ||
                $this->getPackageParameters('jelix/jelix-essential') ||
                $this->getPackageParameters('jelix/for-classic-package')  // deprecated
            ) {
                return false;
            }

            if (file_exists($this->appDir.'app/system/mainconfig.ini.php')) {
                $this->jelixTarget = '1.7';
                return false;
            }

            if (file_exists($this->varConfigDir . 'mainconfig.ini.php')) {
                $this->jelixTarget = '1.6';
                return true;
            }
        }
        else if ($this->jelixTarget == '1.6') {
            return true;
        }
        return false;
    }
}
