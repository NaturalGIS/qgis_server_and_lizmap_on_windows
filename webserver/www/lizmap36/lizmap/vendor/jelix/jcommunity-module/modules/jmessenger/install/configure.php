<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2022 Laurent Jouanneau
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
use \Jelix\Installer\Module\API\ConfigurationHelpers;
use Jelix\Routing\UrlMapping\EntryPointUrlModifier;
use Jelix\Routing\UrlMapping\MapEntry\MapInclude;
use Jelix\Routing\UrlMapping\MapEntry\ModuleUrl;

class jmessengerModuleConfigurator extends \Jelix\Installer\Module\Configurator
{
    public function getDefaultParameters()
    {
        return array(
        );
    }

    public function declareUrls(EntryPointUrlModifier $registerOnEntryPoint)
    {
        $registerOnEntryPoint->havingName(
            'admin',
            array(
                new MapInclude('urls.xml', '/messenger'),
            )
        )
        ;
    }

    public function configure(ConfigurationHelpers $helpers)
    {

    }
}

