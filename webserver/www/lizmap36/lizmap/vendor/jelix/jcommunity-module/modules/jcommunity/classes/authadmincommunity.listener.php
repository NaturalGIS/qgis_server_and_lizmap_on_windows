<?php
/**
* @package      jcommunity
* @subpackage
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2009-2023 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

use \Jelix\JCommunity\FormPassword;

/**
 * Listener for events emitted by the jauthdb_admin
 *
 * We should not display all field in the account form displayed
 * by jauthdb_admin
 */
class authadmincommunityListener extends jEventListener{

    /**
     * Modify the form used by a user to view his profile
     *
     * @param jEvent $event
     * @return void
     * @throws jExceptionSelector
     */
    function onjauthdbAdminGetViewInfo(jEvent $event) {
        $form = $event->form;
        if ($event->himself || !jAcl2::check('auth.users.view')) {
            $form->deactivate('status');
            $form->deactivate('create_date');
        }
        $form->deactivate('keyactivate');
        $form->deactivate('request_date');
        /** @var jTpl $tpl */
        $tpl = $event->tpl;

        $canChangePassword = $tpl->get('canChangePass');

        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin() && $canChangePassword) {
            $tpl->assign('canChangePass', false);
            $links = $tpl->get('otherLinks');
            $status = $form->getData('status');
            if ($status == \Jelix\JCommunity\Account::STATUS_NEW) {
                $links[] = array(
                    'url' => jUrl::get('jcommunity~registration_admin_resend:index', array('login'=>$tpl->get('id'))),
                    'label' => jLocale::get('jcommunity~account.admin.link.account.resent.validation.email'),
                );
            }
            else {
                $links[] = array(
                    'url' => jUrl::get('jcommunity~password_reset_admin:index', array('login'=>$tpl->get('id'))),
                    'label' => jLocale::get('jcommunity~account.admin.link.account.reset.password'),
                );
            }
            $tpl->assign('otherLinks', $links);
        }
    }

    /**
     * Modify the form to edit the profile a user, during creation of the form
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminPrepareUpdate(jEvent $event)
    {
        $form = $event->form;
        if ($event->himself || !jAcl2::check('auth.users.view')) {
            $form->deactivate('status');
            $form->deactivate('create_date');
        }
        $form->deactivate('keyactivate');
        $form->deactivate('request_date');
    }

    /**
     * Modify the form to edit the profile a user, during display of the form
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminEditUpdate(jEvent $event)
    {
        $this->onjauthdbAdminPrepareUpdate($event);
    }

    protected function createValidationWidget()
    {
        $rbuttons = new jFormsControlRadiobuttons('jcommFirstStatus');
        $rbuttons->label = jLocale::get('jcommunity~account.form.status');
        $rbuttons->datasource = new jFormsStaticDatasource();
        $rbuttons->datasource->data = array(
            'STATUS_NEW' => jLocale::get('jcommunity~account.form.admin.registration.info'),
            'STATUS_VALID' => jLocale::get('jcommunity~account.form.status.valid'),
        );
        return $rbuttons;
    }

    /**
     * modify the form used to create a new user, during the creation of the form
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminPrepareCreate(jEvent $event)
    {
        /** @var jFormsBase $form */
        $form = $event->form;
        $form->deactivate('status');
        $form->deactivate('create_date');
        $form->deactivate('keyactivate');
        $form->deactivate('request_date');

        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin()) {
            $form->deactivate('password');
            $form->deactivate('password_confirm');
            $form->addControl($this->createValidationWidget());
            $form->setData('jcommFirstStatus', 'STATUS_NEW');
        }
        else {
            $form->getControl('password')->required = true;
        }
    }

    /**
     * modify the form used to create a new user, during the display of the form
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminEditCreate(jEvent $event)
    {
        /** @var jFormsBase $form */
        $form = $event->form;
        $form->deactivate('status');
        $form->deactivate('create_date');
        $form->deactivate('keyactivate');
        $form->deactivate('request_date');
        $event->tpl->assign('randomPwd', '');

        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin()) {
            $form->deactivate('password');
            $form->deactivate('password_confirm');
            $form->addControl($this->createValidationWidget());
        }
        else {
            /** @var \jTpl $tpl */
            $tpl = $event->tpl;

            $formOptions = $tpl->get('formOptions');
            if (is_array($formOptions) && FormPassword::canUseSecretEditor()) {
                // this is Jelix 1.8.3
                $formOptions['plugins']['password'] = 'passwordeditor_html';
                $tpl->assign('formOptions', $formOptions);
                $form->deactivate('password_confirm');
            }
            $form->getControl('password')->required = true;
        }
    }

    /**
     * Modify the form before checking the form used to create a new user
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminBeforeCheckCreateForm(jEvent $event)
    {
        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin()) {
            $event->form->addControl($this->createValidationWidget());
        }
        else {
            $event->form->getControl('password')->required = true;
        }
    }

    /**
     * Additional checks during the validation of the form used to
     * create a new user
     *
     * @param jEvent $event
     * @return void
     */
    function onjauthdbAdminCheckCreateForm(jEvent $event)
    {
        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin()) {
            $firstStatus = $event->form->getData('jcommFirstStatus') == 'STATUS_VALID' ?
                \Jelix\JCommunity\Account::STATUS_VALID :
                \Jelix\JCommunity\Account::STATUS_NEW;
            $event->form->setData('status', $firstStatus);
            $pwd = \jAuth::getRandomPassword();
            $event->form->setData('password', $pwd);
            $event->form->setData('password_confirm', $pwd);
        }
        else {
            if (FormPassword::canUseSecretEditor() && !FormPassword::checkPassword($event->form->getData('password'))) {
                $event->form->setErrorOn('password', jLocale::get('jelix~jforms.password.not.strong.enough'));
                $event->add(['check' => false]);
            }
            $event->form->setData('status', \Jelix\JCommunity\Account::STATUS_VALID);
        }
    }

    /**
     * Additional process after saving the profile of a new user.
     *
     * @param jEvent $event
     * @return void
     * @throws jExceptionSelector
     */
    function onjauthdbAdminAfterCreate(jEvent $event)
    {
        $config = new \Jelix\JCommunity\Config();
        $sendEmail = $event->form->getData('jcommFirstStatus');
        if ($config->isResetAdminPasswordEnabledForAdmin() && $sendEmail == 'STATUS_NEW') {
            $registration = new \Jelix\JCommunity\Registration(\jAuth::getUserSession()->email);
            try {
                $registration->createUserByAdmin($event->user);
            } catch(\PHPMailer\PHPMailer\Exception $e) {
                \jLog::logEx($e, 'error');
                jMessage::add(jLocale::get('jcommunity~password.form.change.error.smtperror'), 'error');
                return;
            } catch(\phpmailerException $e) {
                \jLog::logEx($e, 'error');
                jMessage::add(jLocale::get('jcommunity~password.form.change.error.smtperror'), 'error');
                return;
            }
            jMessage::add(jLocale::get('jcommunity~account.form.admin.create.emailsent'));
        }
    }

    function onjauthdbAdminPasswordForm(jEvent $event)
    {
        /** @var \jTpl $tpl */
        $tpl = $event->tpl;

        $formOptions = $tpl->get('formOptions');
        if (is_array($formOptions) && FormPassword::canUseSecretEditor()) {
            // this is Jelix 1.8.3
            $formOptions['plugins']['pwd'] = 'passwordeditor_html';
            $tpl->assign('formOptions', $formOptions);
            $event->form->deactivate('pwd_confirm');
            $tpl->assign('randomPwd', '');
        }
    }

    function onjauthdbAdminCheckPasswordForm(jEvent $event)
    {
        if (FormPassword::canUseSecretEditor() && !FormPassword::checkPassword($event->form->getData('pwd'))) {
            \jLog::log('not strong enough');
            $event->form->setErrorOn('pwd', jLocale::get('jelix~jforms.password.not.strong.enough'));
            $event->add(['check' => false]);
        }
    }
}
