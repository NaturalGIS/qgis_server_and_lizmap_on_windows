<?php
/**
* @author       Laurent Jouanneau <laurent@jelix.org>
* @copyright    2015 Laurent Jouanneau
*
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

namespace Jelix\JCommunity;

class Config
{
    protected $responseType = 'html';

    protected $registrationEnabled = true;

    protected $resetPasswordEnabled = true;

    protected $resetAdminPasswordEnabled = true;

    protected $passwordChangeEnabled = true;

    protected $accountDestroyEnabled = true;

    protected $verifyNickname = true;

    protected $publicProperties = array('login', 'nickname', 'create_date');

    protected $notifyAccountChange = false;

    protected $notificationReceiverName = '';

    protected $notificationReceiverEmail = '';

    /**
     * @var integer  TTL in minutes
     */
    protected $validationKeyTTL = 1440; // 24h

    /**
     * Indicate if jcommunity should take care of this following rights:
     * - auth.user.modify
     * - auth.user.change.password
     * @var bool
     */
    protected $useJAuthDbAdminRights = false;

    /**
     */
    public function __construct()
    {
        $config = (isset(\jApp::config()->jcommunity) ? \jApp::config()->jcommunity : array());

        foreach(array(
            'responseType' => 'loginResponse',
            'verifyNickname' => 'verifyNickname',
            'passwordChangeEnabled' => 'passwordChangeEnabled',
            'accountDestroyEnabled' => 'accountDestroyEnabled',
            'useJAuthDbAdminRights' => 'useJAuthDbAdminRights',
            'validationKeyTTL' => 'validationKeyTTL',
            'notifyAccountChange' => 'notifyAccountChange',
            'notificationReceiverName' => 'notificationReceiverName',
            'notificationReceiverEmail' => 'notificationReceiverEmail',
                ) as $prop => $param) {
            if (array_key_exists($param, $config)) {
                $this->$prop = $config[$param];
            }
        }


        if (array_key_exists('publicProperties', $config)) {
            if (!is_array($config['publicProperties'])) {
                $this->publicProperties = preg_split('/\s*,\s*/', trim($config['publicProperties']));
            }
            else {
                $this->publicProperties = $config['publicProperties'];
            }
        }

        if (array_key_exists('disableJPref', $config) && $config['disableJPref'] == false &&
            class_exists('jPref')
        ) {
            $pref = \jPref::get('jcommunity_registrationEnabled');
            if ($pref !== null) {
                $this->registrationEnabled = $pref;
            }
            $pref = \jPref::get('jcommunity_resetPasswordEnabled');
            if ($pref !== null) {
                $this->resetPasswordEnabled = $pref;
            }
            $pref = \jPref::get('jcommunity_resetAdminPasswordEnabled');
            if ($pref !== null) {
                $this->resetAdminPasswordEnabled = $pref;
            }
        } else {
            if (array_key_exists('registrationEnabled', $config)) {
                $this->registrationEnabled = (bool) $config['registrationEnabled'];
            }
            if (array_key_exists('resetPasswordEnabled', $config)) {
                $this->resetPasswordEnabled = (bool) $config['resetPasswordEnabled'];
            }
            if (array_key_exists('resetAdminPasswordEnabled', $config)) {
                $this->resetAdminPasswordEnabled = (bool) $config['resetAdminPasswordEnabled'];
            }
        }
        $sender = filter_var(\jApp::config()->mailer['webmasterEmail'], FILTER_VALIDATE_EMAIL);
        if (!$sender) {
            // if the sender email is not configured, deactivate features that
            // need to send an email
            $this->resetPasswordEnabled = false;
            $this->resetAdminPasswordEnabled = false;
            $this->registrationEnabled = false;
        }
    }

    public function getResponseType()
    {
        return $this->responseType;
    }

    public function isRegistrationEnabled()
    {
        return $this->registrationEnabled;
    }

    public function isResetPasswordEnabled()
    {
        return $this->resetPasswordEnabled;
    }

    public function isResetAdminPasswordEnabled()
    {
        return $this->resetAdminPasswordEnabled;
    }

    public function isResetAdminPasswordEnabledForAdmin()
    {
        if ($this->useJAuthDbAdminRights) {
            return $this->resetAdminPasswordEnabled &&
                \jAcl2::check('auth.users.change.password');
        }
        return $this->resetAdminPasswordEnabled;
    }

    public function isPasswordChangeEnabled()
    {
        if ($this->useJAuthDbAdminRights) {
            return $this->passwordChangeEnabled &&
                \jAcl2::check('auth.user.change.password');
        }
        return $this->passwordChangeEnabled;
    }

    public function isAccountChangeEnabled() {
        if ($this->useJAuthDbAdminRights) {
            return \jAcl2::check('auth.user.modify');
        }
        return true;
    }

    public function mustAccountChangeBeNotified()
    {
        list($email, $name) = $this->getNotificationReceiver();
        return $this->notifyAccountChange && ($email != '');
    }

    public function isAccountDestroyEnabled() {
        return $this->accountDestroyEnabled && $this->isAccountChangeEnabled();
    }

    public function verifyNickname()
    {
        return $this->verifyNickname;
    }

    /**
     * @return \DateInterval
     * @throws \Exception
     */
    public function getValidationKeyTTL()
    {
        $ttl = intval($this->validationKeyTTL);
        if ($ttl < 5) {
            $ttl = 5;
        }
        else if ($ttl > 10080) {
            $ttl = 10080;
        }
        $dt = new \DateInterval('PT'.$ttl.'M');
        return $dt;
    }

    public function getValidationKeyTTLAsString()
    {
        $dt = $this->getValidationKeyTTL();
        $from = new \DateTime();
        $to = clone $from;
        $to = $to->add($dt);
        $ttl = $from->diff($to);

        $str = '';
        if ($ttl->d > 0) {
            $str .= $ttl->d . ' '.\jLocale::get('jcommunity~account.duration.day'.($ttl->d > 1?'s':''));
        }
        if ($ttl->h > 0) {
            $str .= ' ' . $ttl->h . ' '.\jLocale::get('jcommunity~account.duration.hour'.($ttl->h > 1?'s':''));
        }
        if ($ttl->i > 0) {
            $str .= ' ' . $ttl->i . ' '.\jLocale::get('jcommunity~account.duration.minute'.($ttl->i > 1?'s':''));
        }

        return trim($str);
    }

    public function getPublicUserProperties()
    {
        if ($this->useJAuthDbAdminRights && ! \jAcl2::check('auth.user.view')) {
            return array('login');
        }
        return $this->publicProperties;
    }


    public function getDomainAndServerURI()
    {
        if (method_exists('jServer', 'getDomainName')) {
            $domain = \jServer::getDomainName();
            $websiteUri =  \jServer::getServerURI();
        }
        else {
            // old version of jelix < 1.7.5 && < 1.6.30
            $domain = \jApp::coord()->request->getDomainName();
            $websiteUri = \jApp::coord()->request->getServerURI();
        }
        return [$domain, $websiteUri];
    }

    /**
     * Give email and name of the user who receives notifications
     *
     * @return array  ['the email', 'name']
     */
    public function getNotificationReceiver()
    {
        if ($this->notificationReceiverEmail == '') {
            $config = \jApp::config()->mailer;
            $email = $config['webmasterEmail'];
            $name = $config['webmasterName'];
        }
        else {
            $email = $this->notificationReceiverEmail;
            $name = $this->notificationReceiverName;
        }
        return [$email, $name];
    }

    /**
     * Helper to send emails
     *
     * @param string $toEmail
     * @param string $subject
     * @param \jTpl $tpl
     * @param string $templateContent
     * @param string $replyTo
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendHtmlEmail($toEmail, $subject, $tpl, $templateContent, $replyTo='')
    {
        list($domain, $websiteUri) = $this->getDomainAndServerURI();

        $mail = new \jMailer();
        $mail->Subject = $subject;
        $mail->AddAddress($toEmail);
        $mail->isHtml(true);

        if ($replyTo) {
            $mail->addReplyTo($replyTo);
        }
        $tpl->assign('domain_name', $domain);
        $basePath = \jApp::urlBasePath();
        $tpl->assign('basePath', ($basePath == '/'?'':$basePath));
        $tpl->assign('website_uri', $websiteUri.($basePath == '/'?'':$basePath));

        $body = $tpl->fetchFromString($templateContent, 'html');
        $mail->msgHTML($body, '', array($mail, 'html2textKeepLinkSafe'));
        try {
            $mail->Send();
        }
        catch(\PHPMailer\PHPMailer\Exception $e) {
            \jLog::logEx($e, 'error');
            return false;
        }
        catch(\Exception $e) {
            \jLog::logEx($e, 'error');
            return false;
        }
        return true;
    }
}
