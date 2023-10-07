<?php
/**
 * @author       Laurent Jouanneau <laurent@jelix.org>
 * @copyright    2018-2019 Laurent Jouanneau
 *
 * @link         http://jelix.org
 * @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
 */

namespace Jelix\JCommunity;

class PasswordReset {

    protected $forRegistration = false;

    protected $byAdmin = false;

    protected $subjectLocaleId = '';

    protected $tplLocaleId = '';


    function __construct($forRegistration = false, $byAdmin = false) {
        $this->forRegistration = $forRegistration;
        $this->byAdmin = $byAdmin;

        if ($byAdmin) {
            $this->subjectLocaleId = 'jcommunity~mail.password.admin.reset.subject';
            $this->tplLocaleId = 'jcommunity~mail.password.admin.reset.body.html';
        }
        else {
            $this->subjectLocaleId = 'jcommunity~mail.password.reset.subject';
            $this->tplLocaleId = 'jcommunity~mail.password.reset.body.html';
        }
    }


    /**
     * @param object $user
     * @return string
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \jException
     * @throws \jExceptionSelector
     */
    function sendEmail($user)
    {

        if (!$user) {
            \jLog::log('A password reset is attempted for unknown user', 'warning');
            return self::RESET_BAD_LOGIN_EMAIL;
        }

        if ($user->email == '') {
            \jLog::log('A password reset is attempted for the user "'.$user->login.'" having no mail', 'warning');
            return self::RESET_BAD_LOGIN_EMAIL;
        }

        if (!\jAuth::canChangePassword($user->login)) {
            return self::RESET_BAD_STATUS;
        }

        if ($user->status != Account::STATUS_VALID &&
            $user->status != Account::STATUS_PWD_CHANGED &&
            $user->status != Account::STATUS_NEW
        ) {
            return self::RESET_BAD_STATUS;
        }

        $key = sha1(password_hash($user->login.$user->email.microtime(),PASSWORD_DEFAULT));
        if ($user->status != Account::STATUS_NEW) {
            $user->status = Account::STATUS_PWD_CHANGED;
        }
        $user->request_date = date('Y-m-d H:i:s');
        $user->keyactivate = ($this->byAdmin?'A:':'U:').$key;

        $config = new Config();
        list($domain, $websiteUri) = $config->getDomainAndServerURI();

        $replyTo = '';
        if ($this->byAdmin) {
            $replyTo = \jAuth::getUserSession()->email;
        }

        $tpl = new \jTpl();
        $tpl->assign('user', $user);
        $tpl->assign('confirmation_link', \jUrl::getFull(
            'jcommunity~password_reset:resetform@classic',
            array('login' => $user->login, 'key' => $key)
        ));

        $tpl->assign('validationKeyTTL', $config->getValidationKeyTTLAsString());

        if (!$config->sendHtmlEmail(
            $user->email,
            \jLocale::get($this->subjectLocaleId, $domain),
            $tpl,
            \jLocale::get($this->tplLocaleId),
            $replyTo)
        ) {
            return self::RESET_MAIL_SERVER_ERROR;
        }

        \jAuth::updateUser($user);

        return self::RESET_OK;
    }

    const RESET_BAD_LOGIN_EMAIL = "badloginemail";

    const RESET_ALREADY_DONE = "alreadydone";
    const RESET_OK = "ok";
    const RESET_BAD_KEY = "badkey";
    const RESET_EXPIRED_KEY = "expiredkey";
    const RESET_BAD_STATUS = "badstatus";
    const RESET_MAIL_SERVER_ERROR = "smtperror";

    /**
     * @param string $login
     * @param string $key
     * @return object|string
     * @throws \Exception
     */
    function checkKey($login, $key)
    {
        if ($login == '' || $key == '') {
            return self::RESET_BAD_KEY;
        }
        $user = \jAuth::getUser($login);
        if (!$user) {
            return self::RESET_BAD_KEY;
        }

        if ($user->keyactivate == '' ||
            $user->request_date == ''
        ) {
            return self::RESET_BAD_KEY;
        }
        $keyactivate = $user->keyactivate;

        if (preg_match('/^([AU]:)(.+)$/', $keyactivate , $m)) {
            $keyactivate = $m[2];
        }

        if ($keyactivate != $key) {
            return self::RESET_BAD_KEY;
        }

        $expectedStatus = ($this->forRegistration? Account::STATUS_NEW : Account::STATUS_PWD_CHANGED);
        if ($user->status != $expectedStatus) {
            if ($user->status == Account::STATUS_VALID) {
                return self::RESET_ALREADY_DONE;
            }
            return self::RESET_BAD_STATUS;
        }

        if (!\jAuth::canChangePassword($login)) {
            return self::RESET_BAD_STATUS;
        }

        $config = new Config();
        $dt = new \DateTime($user->request_date);
        $dtNow = new \DateTime();
        $dt->add($config->getValidationKeyTTL());
        if ($dt < $dtNow ) {
            return self::RESET_EXPIRED_KEY;
        }
        return $user;
    }

    function changePassword($user, $newPassword) {
        $user->status = Account::STATUS_VALID;
        $user->keyactivate = '';
        \jAuth::updateUser($user);
        \jAuth::changePassword($user->login, $newPassword);
    }

}
