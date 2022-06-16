<?php

namespace Jelix\ComposerPlugin;


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
     *
     * @param  JelixParameters  $parameters
     * @param  null  $logger
     */
    function __construct(JelixParameters $parameters, $logger = null) {
        $this->parameters = $parameters;
        $this->logger = $logger;
    }

    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->log($message);
        }
    }

    function setup() {
        $this->log("--- Setup jelix17 starts");
        $allModulesDir = $this->parameters->getAllModulesDirs();
        $allPluginsDir = $this->parameters->getAllPluginsDirs();
        $allModules = $this->parameters->getAllSingleModuleDirs();

        $php = '<'.'?php'."\n";

        if (count($allModulesDir)) {
            $php .= <<<EOF
jApp::declareModulesDir(array(

EOF;
            foreach($allModulesDir as $dir) {
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
            foreach($allModules as $dir) {
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
            foreach($allPluginsDir as $dir) {
                $php .= <<<EOF
            __DIR__.'/$dir',

EOF;
            }
            $php .= "));\n";
        }
        file_put_contents($this->parameters->getVendorDir().'jelix_app_path.php', $php);
        $this->log("Setup jelix17 ends");
    }
}
