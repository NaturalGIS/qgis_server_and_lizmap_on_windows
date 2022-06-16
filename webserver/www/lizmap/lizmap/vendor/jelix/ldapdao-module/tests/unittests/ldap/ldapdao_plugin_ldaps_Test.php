<?php
/**
 * @package     ldapdao
 * @author      laurent Jouanneau
 * @copyright   2019 laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */


require_once(__DIR__.'/ldapdao_plugin_trait.php');

/**
 * Tests API driver LDAP/DAO for jAuth, with ldaps protocol
 * @package     ldapdao
 */
class ldapdao_plugin_ldaps_AuthTest  extends jUnitTestCase {
    use ldapdao_plugin_trait;


    protected $ldapPort = 636;

    protected $ldapTlsMode = 'ldaps';

    protected $ldapProfileName = 'ldapsdao';
}
