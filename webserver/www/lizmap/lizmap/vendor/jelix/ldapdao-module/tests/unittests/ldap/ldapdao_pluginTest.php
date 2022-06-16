<?php
/**
 * @package     ldapdao
 * @author      laurent Jouanneau
 * @copyright   2017 laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */


require_once(__DIR__.'/ldapdao_plugin_trait.php');

/**
 * Tests API driver LDAP/DAO for jAuth, with no encrypted communication
 * @package     ldapdao
 */
class ldapdao_pluginAuthTest extends jUnitTestCase {
    use ldapdao_plugin_trait;

    protected $ldapPort = 389;

    protected $ldapTlsMode = '';

    protected $ldapProfileName = 'ldapdao';
}
