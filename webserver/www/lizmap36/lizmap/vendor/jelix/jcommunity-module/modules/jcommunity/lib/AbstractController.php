<?php
/**
* @author       Laurent Jouanneau <laurent@jelix.org>
* @copyright    2015-2019 Laurent Jouanneau
*
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

namespace Jelix\JCommunity;

class AbstractController extends \jController
{
    protected $configMethodCheck = '';

    protected $checkIsConnected = true;

    protected $responseId = '';

    /**
     * @var Config
     */
    protected $config;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->config = new Config();
    }

    /**
     * verify that the user is allowed to access to the controller
     *
     * Called by actions
     *
     * @return \jResponse|null null if it is ok, else a response to redirect to an error page
     */
    protected function _check()
    {
        if ($this->configMethodCheck) {
            $method = $this->configMethodCheck;
            if (!$this->config->$method()) {
                return $this->notavailable();
            }
        }
        if ($this->checkIsConnected && \jAuth::isConnected()) {
            return $this->noaccess('no_access_auth');
        }

        return null;
    }

    protected function canViewProfiles($login) {
        $himself = ($login != ''  && \jAuth::isConnected() && \jAuth::getUserSession()->login == $login);
        return ($himself || \jAcl2::check('auth.users.view'));
    }

    protected function _getjCommunityResponse($windowTitle, $pageTitle='')
    {
        $response = 'html';
        if ($this->responseId == ''  && isset(\jApp::config()->jcommunity)) {
            $conf = \jApp::config()->jcommunity;
            $response = (isset($conf['loginResponse']) ? $conf['loginResponse'] : 'html');
        }

        $rep = $this->getResponse($response);
        $rep->title = $windowTitle;
        if ($pageTitle == '') {
            $pageTitle = $windowTitle;
        }
        if ($response == 'htmlauth') {
            $rep->body->assign('page_title', $pageTitle);
        }
        return $rep;
    }

    protected function noaccess($errorId = '')
    {
        $rep = $this->_getjCommunityResponse('Forbidden');
        $rep->setHttpStatus(403, 'Forbidden');
        return $this->showError($rep, $errorId);
    }

    protected function showError($rep, $errorId)
    {

        $tpl = new \jTpl();
        $canViewProfile = $this->canViewProfiles('');
        if (\jAuth::isConnected()) {
            $tpl->assign('login', \jAuth::getUserSession()->login);
        }
        else {
            $tpl->assign('login', '');
        }
        $tpl->assign('error', $errorId);
        $tpl->assign('canViewProfile', $canViewProfile);
        $rep->body->assign('MAIN', $tpl->fetch('error'));

        return $rep;
    }

    protected function notavailable($errorId = 'not_available')
    {
        $rep = $this->_getjCommunityResponse('Not found');
        $rep->setHttpStatus(404, 'Not found');
        return $this->showError($rep, $errorId);
    }

    protected function badParameters()
    {
        $rep = $this->_getjCommunityResponse('Bad request');
        $rep->setHttpStatus(400, 'Bad request');
        return $this->showError($rep, 'Invalid parameters');
    }
}
