<?php

namespace Jelix\ComposerPlugin;


use Composer\Util\Filesystem;
use Jelix\ComposerPlugin\Ini\IniModifier;
use Jelix\ComposerPlugin\Ini\IniModifierInterface;

/**
 * Setup configuration for a Jelix 1.7+ application
 */
class SetupJelix17 {

    /**
     * @var JelixParameters
     */
    protected $parameters;

    /**
     * @var DebugLogger|null
     */
    protected $logger;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     *
     * @param  JelixParameters  $parameters
     * @param  null  $logger
     */
    function __construct(JelixParameters $parameters, $logger = null) {
        $this->parameters = $parameters;
        $this->logger = $logger;
        $this->fs = new Filesystem();
    }

    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->log($message);
        }
    }

    function setup()
    {
        $this->log("--- Setup jelix17 starts");
        $allModulesDir = $this->parameters->getAllModulesDirs();
        $allPluginsDir = $this->parameters->getAllPluginsDirs();
        $allModules = $this->parameters->getAllSingleModuleDirs();

        $php = '<' . '?php' . "\n";

        if (count($allModulesDir)) {
            $php .= <<<EOF
jApp::declareModulesDir(array(

EOF;
            foreach ($allModulesDir as $dir) {
                $php .= <<<EOF
            __DIR__.'/$dir',

EOF;
            }
            $php .= "));\n";
        }

        if (count($allModules)) {
            $php .= <<<EOF
jApp::declareModule(array(

EOF;
            foreach ($allModules as $dir) {
                $php .= <<<EOF
            __DIR__.'/$dir',

EOF;
            }
            $php .= "));\n";
        }

        if (count($allPluginsDir)) {
            $php .= <<<EOF
jApp::declarePluginsDir(array(

EOF;
            foreach ($allPluginsDir as $dir) {
                $php .= <<<EOF
            __DIR__.'/$dir',

EOF;
            }
            $php .= "));\n";
        }
        file_put_contents($this->parameters->getVendorDir() . 'jelix_app_path.php', $php);

        $ini = $this->loadLocalConfigFile();
        if ($ini) {
            $this->setupConfig($ini, $allModules, $allModulesDir);
            $ini->save();
        }

        $this->log("Setup for Jelix 1.7+ ends");
    }

    protected function setupConfig(IniModifierInterface $ini, $allModules, $allModulesDir)
    {
        // we remove all `.path` key from the configuration that has been added
        // by the plugin for Jelix 1.6, in case the application was for Jelix 1.6 and
        // has been upgraded for Jelix 1.7

        foreach($allModules as $path) {
            $path = $this->getFinalPath($path);
            $moduleName = basename($path);
            $this->log("remove from config $moduleName.path");
            $ini->removeValue($moduleName.'.path', 'modules');
        }

        if (count($allModulesDir)) {
            // remove all path of modules that are inside modules directories
            foreach($allModulesDir as $path) {
                $path = $this->getFinalPath($path);
                if ($handle = opendir($path)) {
                    while (($name = readdir($handle)) !== false) {
                        if ($name[0] != '.' && is_dir($path.$name)) {
                            $this->log("remove from config $name.path");
                            $ini->removeValue($name.'.path', 'modules');
                        }
                    }
                    closedir($handle);
                }
            }
        }

        // remove path of modules that do not exist anymore into the vendor
        // directory (it could be modules that disappear from packages because
        // not compatible with jelix 1.7)
        $vendorPath = $this->getFinalPath('./');
        $vendorDir = $this->parameters->getVendorDir();
        foreach($ini->getValues('modules') as $key => $val) {
            if (preg_match("/\\.path$/", $key)) {
                $path = str_replace('app:', $this->parameters->getAppDir().'/', $val);
                $path = $this->fs->normalizePath($path);

                if (strpos($path, $vendorPath) === 0) {
                    $path = str_replace($vendorPath, $vendorDir, $path);
                    if (!file_exists($path)) {
                        $this->log("remove from config $key=$val, converted path=$path");
                        $ini->removeValue($key, 'modules');
                    }
                }
            }
        }
    }

    /**
     * @return IniModifier
     * @throws \Exception
     */
    protected function loadLocalConfigFile()
    {
        $configDir = $this->parameters->getVarConfigDir();

        // open the configuration file
        $iniFileName= $configDir.'localconfig.ini.php';
        if (!file_exists($iniFileName)) {
            if (!file_exists($configDir)) {
                // the application may not be exists yet
                return null;
            }
            file_put_contents($iniFileName, "<"."?php\n;die(''); ?".">\n\n");
        }
        $ini = new IniModifier($iniFileName);
        return $ini;
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
        return $appDir.$path;
    }
}
