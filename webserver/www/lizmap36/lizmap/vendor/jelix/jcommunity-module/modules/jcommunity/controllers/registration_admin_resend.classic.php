<?php
/**
 * @author       Laurent Jouanneau <laurent@jelix.org>
 * @contributor
 *
 * @copyright    2019 Laurent Jouanneau
 *
 * @link         http://jelix.org
 * @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
 */

/**
 * controller for an admin to resend the email + new validation key, when the user has
 * created an account
 */
class registration_admin_resendCtrl extends \Jelix\JCommunity\AbstractController
{

    public $pluginParams = array(
        '*' => array('auth.required' => true)
    );

    protected function _checkadmin()
    {
        if (!$this->config->isResetAdminPasswordEnabledForAdmin()) {
            return $this->notavailable();
        }
        return null;
    }

    protected function _checkUser($login, $rep)
    {
        $user = \jAuth::getUser($login);
        if (!$user || $user->email == '') {
            $this->showError($rep, 'no_access_wronguser');
            return false;
        }

        if (!jAuth::canChangePassword($login)) {
            $error = 'no_access_badstatus';
            if (method_exists('jAuth', 'getReasonToForbiddenPasswordChange')) {
                // new in Jelix 1.6.37
                $reason = jAuth::getReasonToForbiddenPasswordChange();
                if ($reason) {
                    $error = $reason;
                }
            }

            $this->showError($rep, $error);
            return false;
        }

        if ($user->status != \Jelix\JCommunity\Account::STATUS_NEW) {
            $this->showError($rep, 'no_access_badstatus');
            return false;
        }
        return $user;
    }

    /**
     * form to confirm to resend the email + new validation key
     */
    public function index()
    {
        $repError = $this->_checkadmin();
        if ($repError) {
            return $repError;
        }

        $rep = $this->_getjCommunityResponse(jLocale::get('register.admin.resend.email.title'), jLocale::get('register.form.create.title'));

        $login = $this->param('login');

        $user = $this->_checkUser($login, $rep);
        if ($user === false) {
            return $rep;
        }

        $tpl = new jTpl();
        $tpl->assign('login', $login);
        $rep->body->assign('MAIN', $tpl->fetch('registration_admin_resend'));

        return $rep;
    }

    /**
     * send an email with a new validation key
     */
    public function send()
    {
        $repError = $this->_checkadmin();
        if ($repError) {
            return $repError;
        }

        $login = $this->param('pass_login');

        $rep = $this->_getjCommunityResponse(jLocale::get('register.admin.resend.email.title'), jLocale::get('register.form.create.title'));
        $user = $this->_checkUser($login, $rep);
        if ($user === false) {
            return $rep;
        }

        $rep = $this->getResponse('redirect');
        $rep->action = 'registration_admin_resend:index';

        $registration = new \Jelix\JCommunity\Registration(\jAuth::getUserSession()->email);
        try {
            $registration->resendRegistrationMail($user, true);
        } catch(\PHPMailer\PHPMailer\Exception $e) {
            \jLog::logEx($e, 'error');
            $rep = $this->_getjCommunityResponse(jLocale::get('register.admin.resend.email.title'), jLocale::get('register.form.create.title'));
            return $this->showError($rep, jLocale::get('jcommunity~password.form.change.error.smtperror'));
        } catch(\phpmailerException $e) {
            \jLog::logEx($e, 'error');
            $rep = $this->_getjCommunityResponse(jLocale::get('register.admin.resend.email.title'), jLocale::get('register.form.create.title'));
            return $this->showError($rep, jLocale::get('jcommunity~password.form.change.error.smtperror'));
        }

        $rep->action = 'registration_admin_resend:sent';
        $rep->params = array('login'=>$login);

        return $rep;
    }

    /**
     * Display the message that confirms the email sending
     *
     * @return jResponse|jResponseHtml|jResponseJson|jResponseRedirect|void
     * @throws Exception
     * @throws jExceptionSelector
     */
    public function sent() {
        $repError = $this->_checkadmin();
        if ($repError) {
            return $repError;
        }

        $rep = $this->_getjCommunityResponse(jLocale::get('register.admin.resend.email.title'), jLocale::get('register.form.create.title'));
        $tpl = new jTpl();
        $tpl->assign('login', $this->param('login'));
        $rep->body->assign('MAIN', $tpl->fetch('registration_admin_resend_done'));

        return $rep;
    }
}
