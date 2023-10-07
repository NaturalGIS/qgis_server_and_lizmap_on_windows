<?php

namespace Jelix\ComposerPlugin;

/**
 * Class to load and save the file vendor/jelix_modules_infos.json
 *
 * @internal Warning: this class should not rely on Composer classes, to be able to use
 * by other packages that need to have information about packages.
 */
class PackagesInformationFile
{
    protected $path;

    function __construct($vendorDir, $filename = '')
    {
        if ($filename == '') {
            $filename = 'jelix_modules_infos.json';
        }
        $this->path = rtrim($vendorDir, '/').'/'.$filename;
    }

    function exists()
    {
        return file_exists($this->path);
    }

    /**
     * @return array with two values: JelixPackageParameters[] and jelixTarget
     */
    function load()
    {
        $content = json_decode(file_get_contents($this->path), true);

        if (!isset($content['packages'])) {
            return array(array(), '');
        }
        $jelixTarget = '';
        if (isset($content['target-jelix-version'])) {
            $jelixTarget = $content['target-jelix-version'];
        }
        $packagesInfos = array();
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
            $packagesInfos[$package] = $parameters;
        }
        return array($packagesInfos, $jelixTarget);
    }

    /**
     * @param JelixPackageParameters[] $packagesInfos
     * @param string $jelixTarget
     * @return void
     */
    function save(array $packagesInfos, $jelixTarget)
    {
        $content = array(
            'version'=>1,
            'target-jelix-version' => $jelixTarget,
            'packages'=>array(),
            ''
        );
        foreach($packagesInfos as $package => $parameters) {
            $content['packages'][$package] = array(
                'modules-dirs'=>$parameters->getModulesDirs(),
                'plugins-dirs'=>$parameters->getPluginsDirs(),
                'modules'=>$parameters->getSingleModuleDirs(),
                'autoconfig-access-16'=>$parameters->getAppModulesAccess(),
                'modules-autoconfig-access-16'=>$parameters->getPackageModulesAccess(),
            );
        }
        file_put_contents($this->path, json_encode($content, JSON_PRETTY_PRINT));
    }



}