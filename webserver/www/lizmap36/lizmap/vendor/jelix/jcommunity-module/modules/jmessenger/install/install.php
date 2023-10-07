<?php
/**
* @package     jmessenger
* @author      Laurent Jouanneau
* @contributor
* @copyright   2022 Laurent Jouanneau
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class jmessengerModuleInstaller extends  \Jelix\Installer\Module\Installer
{
    public function install(Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        $helpers->database()->execSQLScript('sql/install');
    }
}