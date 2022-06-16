<?php

namespace Jelix\ComposerPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\CompletePackage;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;
use Composer\Script\ScriptEvents;
use Composer\Installer\PackageEvent;

/**
 * Main class of the plugin for Compose.
 *
 * This class should load our classes only during onPost* methods,
 * to be sure to load latest version of the plugin.
 *
 * @package Jelix\ComposerPlugin
 */
class ModuleSetup  implements PluginInterface, EventSubscriberInterface {

    const VERSION = 1;
    protected $composer;
    protected $io;
    protected $vendorDir;
    protected $packages = array();

    protected $debugEnabled = false;

    protected $debugLogger = null;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->vendorDir = $this->composer->getConfig()->get('vendor-dir').DIRECTORY_SEPARATOR;
        $this->debugEnabled = (getenv('JELIX_DEBUG_COMPOSER') === 'true');
        if (!$this->debugEnabled) {
            $this->debugEnabled = file_exists($this->vendorDir . 'JELIX_DEBUG_COMPOSER');
        }
        if ($this->debugEnabled) {
            $this->debugLogger = new DebugLogger(
                $this->vendorDir . 'jelix_debug.log'
            );
        }
        else {
            $this->debugLogger = new DummyLogger();
        }

        $this->debugLogger->log("\n***** Composer started, plugin activated *****");
    }

    public static function getSubscribedEvents()
    {
        return array(
            PackageEvents::POST_PACKAGE_INSTALL => array(
                array('onPackageInstalled', 0)
            ),
            PackageEvents::POST_PACKAGE_UPDATE => array(
                array('onPackageUpdated', 0)
            ),
            PackageEvents::PRE_PACKAGE_UNINSTALL => array(
                array('onPackageUninstall', 0)
            ),
            ScriptEvents::POST_INSTALL_CMD => array(
                array('onPostInstall', 0)
            ),
            ScriptEvents::POST_UPDATE_CMD => array(
                array('onPostUpdate', 0)
            ),
        );
    }

    protected function mustIgnorePackage(PackageInterface $package)
    {
        $result = $package->getType() == 'jelix-module' ||
                  $package->getName() == 'jelix/jelix' ||
                  $package->getName() == 'jelix/jelix-essential' ||
                  $package->getName() == 'jelix/for-classic-package' // deprecated
        ;
        return !$result;
    }

    public function onPackageInstalled(PackageEvent $event)
    {
        /** @var CompletePackage $installedPackage */
        $installedPackage = $event->getOperation()->getPackage();

        //-----
        /*echo "\n --- packageInstalled : \n   package: ". $installedPackage->getName()."\n"; //var_export($installedPackage->getRepository());

        $repo = $installedPackage->getRepository();
        echo "   is repo localrepo of event:".($repo === $event->getLocalRepo()?'yes':'no')."\n";
        echo "   event->localRepo : "; echo get_class($event->getLocalRepo())."\n";//var_export($event->getLocalRepo());echo "\n";

        $packages = $repo->getPackages();
        foreach ($packages as $packName => $package) {
            echo "\n repo package: ".$packName." - ".$package->getName(). " - ". get_class($package)."\n";
            $packRepo = $package->getRepository();
            echo "   has repo:".($repo === $packRepo?'yes':'no')."\n";
        }

        echo "\n\n";*/
        //-----


        //$this->io->write("=== ModuleSetup === installed package ".$installedPackage->getName()." (".$installedPackage->getType().")");
        if ($this->mustIgnorePackage($installedPackage)) {
            return;
        }
        $packagePath = $this->vendorDir.$installedPackage->getPrettyName();
        $this->packages[] = array('installed', $installedPackage->getName(), $installedPackage->getExtra(), $packagePath);
        $this->debugLogger->log("onPackageInstalled: ".$installedPackage->getName());
    }

    public function onPackageUpdated(PackageEvent $event)
    {
        $initialPackage = $event->getOperation()->getInitialPackage();
        $targetPackage = $event->getOperation()->getTargetPackage();
        //$this->io->write("=== ModuleSetup === updated package ".$targetPackage->getName()." (".$targetPackage->getType().")");
        if ($this->mustIgnorePackage($targetPackage)) {
            return;
        }
        $packagePath = $this->vendorDir.$targetPackage->getPrettyName();
        $this->packages[] = array('updated', $targetPackage->getName(), $targetPackage->getExtra(), $packagePath);
        $this->debugLogger->log("onPackageUpdated: ".$targetPackage->getName());
    }

    public function onPackageUninstall(PackageEvent $event)
    {
        // note to myself: the package files are still there at this step
        $removedPackage = $event->getOperation()->getPackage();
        if ($this->mustIgnorePackage($removedPackage)) {
            return;
        }
        $this->packages[] = array('removed', $removedPackage->getName(), $removedPackage->getExtra());
        $this->debugLogger->log("onPackageUninstall: ".$removedPackage->getName());
    }

    public function onPostInstall(\Composer\Script\Event $event)
    {
        $this->debugLogger->log("onPostInstall");
        $jelixParameters = new JelixParameters($this->vendorDir);
        $jsonInfosFile = $this->vendorDir.'jelix_modules_infos.json';
        if (file_exists($jsonInfosFile)) {
            $jelixParameters->loadFromFile($jsonInfosFile);
        }

        foreach($this->packages as $packageInfo) {
            $action = $packageInfo[0];
            if ($action == 'removed') {
                $jelixParameters->removePackage($packageInfo[1], $packageInfo[2]);
            }
            else {
                try {
                    list($action, $name, $extra, $path) = $packageInfo;
                    $jelixParameters->addPackage($name, $extra, $path, false);
                } catch (ReaderException $e) {
                    $this->io->writeError($e->getMessage());
                }
            }
        }

        // let's add the app package
        try {
            $appPackage = $this->composer->getPackage();
            $jelixParameters->addPackage($appPackage->getName(), $appPackage->getExtra(), getcwd(), true);
        } catch (ReaderException $e) {
            $this->io->writeError($e->getMessage());
        }

        $jelixParameters->saveToFile($jsonInfosFile);

        if ($jelixParameters->getPackageParameters('jelix/jelix') ||
            $jelixParameters->getPackageParameters('jelix/jelix-essential') ||
            $jelixParameters->getPackageParameters('jelix/for-classic-package')  // deprecated
        ) {
            $setup = new SetupJelix17($jelixParameters, $this->debugLogger);
            $setup->setup();
        } else {
            $setup = new SetupJelix16($jelixParameters, $this->debugLogger);
            $setup->setup();
        }
    }

    public function onPostUpdate(\Composer\Script\Event $event)
    {
        $this->onPostInstall($event);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {

    }

    public function uninstall(Composer $composer, IOInterface $io)
    {

    }

    /**
     * @return array  each item is an array containing:
     *      - 'installed', 'updated', or 'removed'
     *      - name of the package
     *      - extra data of the package
     */
    public function getPackagesActions() {
        return $this->packages;
    }
}
