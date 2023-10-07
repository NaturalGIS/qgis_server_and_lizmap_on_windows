<?php

namespace Jelix\ComposerPlugin;


class PostInstaller
{

    protected $vendorDir;

    /**
     * @var \Composer\IO\IOInterface
     */
    protected $io;

    protected $debugLogger = null;

    /**
     * @param $vendorDir
     * @param \Composer\IO\IOInterface $io
     */
    public function __construct($vendorDir, $io = null, $debugLogger = null)
    {
        $this->vendorDir = $vendorDir;
        $this->io = $io;
        if ($debugLogger === null) {
            $debugLogger = new DummyLogger();
        }
        $this->debugLogger = $debugLogger;
    }

    protected function writeError($error)
    {
        if ($this->io) {
            $this->io->writeError($error);
        }
        else {
            echo $error."\n";
        }
    }

    public function process($packages, $appPackage)
    {
        $jelixParameters = new JelixParameters($this->vendorDir);
        $jelixParameters->loadFromFile();

        foreach($packages as $packageInfo) {
            $action = $packageInfo[0];
            if ($action == 'removed') {
                $jelixParameters->removePackage($packageInfo[1], $packageInfo[2]);
            }
            else {
                try {
                    list($action, $name, $extra, $path) = $packageInfo;
                    $jelixParameters->addPackage($name, $extra, $path);
                } catch (ReaderException $e) {
                    $this->writeError($e->getMessage());
                }
            }
        }

        // let's add the app package
        try {
            $jelixParameters->addApplicationPackage($appPackage->getName(), $appPackage->getExtra(), getcwd());
        } catch (ReaderException $e) {
            $this->writeError($e->getMessage());
        }

        $jelixParameters->saveToFile();

        if ($jelixParameters->isJelix16()) {
            $setup = new SetupJelix16($jelixParameters, $this->debugLogger);
            $setup->setup();
        } else {
            $setup = new SetupJelix17($jelixParameters, $this->debugLogger);
            $setup->setup();
        }
    }
}