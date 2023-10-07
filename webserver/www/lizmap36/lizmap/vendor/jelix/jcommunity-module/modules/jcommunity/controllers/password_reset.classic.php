<?php
/**
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
*
* @copyright    2007-2019 Laurent Jouanneau
*
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

/**
 * controller for the password reset process, when a user has forgotten his
 * password, and want to change it
 */
class password_resetCtrl extends \Jelix\JCommunity\AbstractPasswordController
{

    /**
     * form to request a password reset.
     */
    public function index()
    {
        $repError = $this->_check();
        if ($repError) {
            return $repError;
        }

        $rep = $this->_getjCommunityResponse(jLocale::get('password.form.title'), jLocale::get('password.page.title'));

        $rep->body->assignZone('MAIN', 'passwordReset');

        return $rep;
    }

    /**
     * send an email to reset the password.
     */
    public function send()
    {
        $repError = $this->_check();
        if ($repError) {
            return $repError;
        }

        $rep = $this->getResponse('redirect');
        $rep->action = 'password_reset:index';

        $form = jForms::fill('password_reset');
        if (!$form) {
            return $this->badParameters();
        }
        if (!$form->check()) {
            return $rep;
        }

        $login = $form->getData('pass_login');

        $driver = \jAuth::getDriver();
        if (\jAuth::getDriverParam('authenticateWith') == 'login-email') {
            $user = null;
            if (method_exists($driver, 'getDao')) {
                $daouser = $driver->getDao();
                if (method_exists($daouser, 'getByLoginOrEmail')) {
                    $user = $daouser->getByLoginOrEmail($login);
                }
            }
            if ($user === null) {
                $user = $daouser->getByLogin($login);
                if (!$user) {
                    $user = $daouser->getByEmail($login);
                }
            }
        }
        else {
            $user = \jAuth::getUser($login);
            $email = $form->getData('pass_email');
            if ($user->email != $email) {
                // bad given email, ignore the error, so no change to discover
                // if a login is associated to an email or not
                jForms::destroy('password_reset');
                $rep->action = 'password_reset:sent';

                return $rep;
            }
        }



        $passReset = new \Jelix\JCommunity\PasswordReset();
        $result = $passReset->sendEmail($user);
        if ($result != $passReset::RESET_OK && $result != $passReset::RESET_BAD_LOGIN_EMAIL) {
            $message = jLocale::get('password.form.change.error.'.$result);
            if (method_exists('jAuth', 'getReasonToForbiddenPasswordChange')) {
                // new in Jelix 1.6.37
                $reason = jAuth::getReasonToForbiddenPasswordChange();
                if ($reason) {
                    $message .= ' ('.$reason.')';
                }
            }
            $form->setErrorOn('pass_login', $message);
            return $rep;
        }

        jForms::destroy('password_reset');
        $rep->action = 'password_reset:sent';

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
        $repError = $this->_check();
        if ($repError) {
            return $repError;
        }

        $rep = $this->_getjCommunityResponse(jLocale::get('password.form.title'), jLocale::get('password.page.title'));
        $tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('password_reset_waiting'));

        return $rep;
    }


    // see other actions into AbstractPasswordController

}
