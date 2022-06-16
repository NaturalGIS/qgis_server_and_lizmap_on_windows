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

    function loadFromFile($filepath)
    {
        $content = json_decode(file_get_contents($filepath), true);
        if (!isset($content['packages'])) {
            return;
        }
        foreach($content['packages'] as $package => $infos) {
            $content = array_merge_recursive(
                array(
                    'is-app'=>false,
                    'modules-dirs'=>array(),
                     'plugins-dirs'=>array(),
                     'modules'=>array(),
                    'autoconfig-access-16'=>array(),
                    'modules-autoconfig-access-16'=>array()
                ),
                $infos
            );
            $parameters = new JelixPackageParameters($package, $content['is-app']);
            $parameters->setModulesDirs($content['modules-dirs']);
            $parameters->setPluginsDirs($content['plugins-dirs']);
            $parameters->setSingleModuleDirs($content['modules']);
            $parameters->setAppModulesAccess($content['autoconfig-access-16']);
            $parameters->setPackageModulesAccess($content['modules-autoconfig-access-16']);
            $this->packagesInfos[$package] = $parameters;
        }
    }

    function saveToFile($filepath)
    {
        $content = array( 'version'=>1, 'packages'=>array());
        foreach($this->packagesInfos as $package => $parameters) {
            $content['packages'][$package] = array(
                'modules-dirs'=>$parameters->getModulesDirs(),
                'plugins-dirs'=>$parameters->getPluginsDirs(),
                'modules'=>$parameters->getSingleModuleDirs(),
                'autoconfig-access-16'=>$parameters->getAppModulesAccess(),
                'modules-autoconfig-access-16'=>$parameters->getPackageModulesAccess(),
            );
        }
        file_put_contents($filepath, json_encode($content, JSON_PRETTY_PRINT));
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
     * @param bool $appPackage indicate if the package is a package loaded by composer (false)
     *             or if it is the application itself (true)
     *
     */
    function addPackage($packageName, $extra, $packagePath, $appPackage=false)
    {
        $parameters = new JelixPackageParameters($packageName, $appPackage);
        $this->packagesInfos[$packageName] = $parameters;

        if(isset($this->removedPackagesInfos[$packageName]))
        {
            unset($this->removedPackagesInfos[$packageName]);
        }

        if (!isset($extra['jelix'])) {
            if ($appPackage) {
                $this->appDir = $packagePath;
            }
            return;
        }

        if ($appPackage) {
            // read informations from the composer.json of the application

            if (isset($extra['jelix']['app-dir'])) {
                if ($this->fs->isAbsolutePath($extra['jelix']['app-dir'])) {
                    $this->appDir = $extra['jelix']['app-dir'];
                }
                else {
                    $this->appDir = $this->fs->normalizePath($packagePath.DIRECTORY_SEPARATOR.$extra['jelix']['app-dir']);
                }
                if (!$this->appDir || !file_exists($this->appDir)) {
                    throw new ReaderException("Error in composer.json of ".$packageName.": extra/jelix/app-dir is not set or does not contain a valid path");
                }
                if (!file_exists($this->appDir.'/project.xml')) {
                    throw new ReaderException("Error in composer.json of ".$packageName.": extra/jelix/app-dir is not a path to a Jelix application");
                }

            }
            else {
                $this->appDir = $packagePath;
                if (!file_exists($this->appDir.'/project.xml')) {
                    throw new ReaderException("The directory of the jelix application cannot be found. Indicate its path into the composer.json of the application, into an extra/jelix/app-dir parameter");
                }
            }
            $this->appDir = rtrim($this->appDir, "/")."/";

            $this->varConfigDir = $this->appDir.'var/config/';

            if (isset($extra['jelix']['var-config-dir'])) {
                if ($this->fs->isAbsolutePath($extra['jelix']['var-config-dir'])) {
                    $this->varConfigDir = $extra['jelix']['var-config-dir'];
                }
                else {
                    $this->varConfigDir = $this->fs->normalizePath($packagePath . DIRECTORY_SEPARATOR . $extra['jelix']['var-config-dir']);
                }
                if (!$this->varConfigDir || !file_exists($this->varConfigDir)) {
                    throw new ReaderException("Error in composer.json of ".$packageName.": extra/jelix/var-config-dir is not set or does not contain a valid path");
                }
                $this->varConfigDir = rtrim($this->varConfigDir, "/")."/";
            }
            else if (!file_exists($this->varConfigDir)) {
                throw new ReaderException("The var/config directory of the jelix application cannot be found. Indicate its path into the composer.json of the application, into an extra/jelix/var-config-dir parameter");
            }

            if (isset($extra['jelix']['config-file-16'])) {
                $this->configurationFileName = $extra['jelix']['config-file-16'];
                if ($this->configurationFileName && !file_exists($this->varConfigDir.$this->configurationFileName)) {
                    throw new ReaderException("The configuration file name indicated into extra/jelix/config-file-16 does not exists into the var/config/ directory of the application");
                }
            }

            if (isset($extra['jelix']['modules-autoconfig-access-16'])) {
                $modulesAccess = $extra['jelix']['modules-autoconfig-access-16'];
                if (is_array($modulesAccess)) {
                    $cleanedModulesAccess = array();
                    foreach($extra['jelix']['modules-autoconfig-access-16'] as $package => $moduleAccess) {
                        if (!is_string($package) || !is_array($moduleAccess)) {
                            continue;
                        }
                        foreach($moduleAccess as $module =>$access) {
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

        }
        else {
            // read informations from the composer.json of a module package

            $this->readAutoconfigAccess($parameters, $extra);
        }

        // read informations that can be in any composer.json

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


    protected function readAutoconfigAccess(JelixPackageParameters $parameters, $extra) {
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
}
