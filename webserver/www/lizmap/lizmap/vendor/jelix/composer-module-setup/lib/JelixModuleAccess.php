<?php

namespace Jelix\ComposerPlugin;


class JelixModuleAccess {

    protected $moduleName;

    protected $access = array();

    /**
     * JelixModuleAccess constructor.
     *
     * @param string $moduleName
     * @param array $accessList array("__global"=> 1, "index" => 2,...)
     */
    function __construct($moduleName, $accessList)
    {
        $this->moduleName = $moduleName;

        foreach($accessList as $ep => $access) {
            $ep = str_replace('.php', '', $ep);
            $this->access[$ep] = $access;
        }
    }

    public function getModuleName()
    {
        return $this->moduleName;
    }

    public function getAccess()
    {
        return $this->access;
    }

}
