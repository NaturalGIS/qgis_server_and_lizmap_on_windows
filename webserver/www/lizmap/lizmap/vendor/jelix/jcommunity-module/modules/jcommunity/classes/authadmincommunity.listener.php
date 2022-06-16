<?php
/**
* @package      jcommunity
* @subpackage
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2009 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

/**
 * Listener for events emitted by the jauthdb_admin
 *
 * We should not display all field in the account form displayed
 * by jauthdb_admin
 */
class authadmincommunityListener extends jEventListener{

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


    function onjauthdbAdminPrepareCreate(jEvent $event)
    {
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
    }

    function onjauthdbAdminEditCreate(jEvent $event)
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
            $event->tpl->assign('randomPwd', '');
        }

    }

    function onjauthdbAdminBeforeCheckCreateForm(jEvent $event)
    {
        $config = new \Jelix\JCommunity\Config();
        if ($config->isResetAdminPasswordEnabledForAdmin()) {
            $event->form->addControl($this->createValidationWidget());
        }
    }

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
            $event->form->setData('status', \Jelix\JCommunity\Account::STATUS_VALID);
        }
    }

    function onjauthdbAdminAfterCreate(jEvent $event)
    {
        $config = new \Jelix\JCommunity\Config();
        $sendEmail = $event->form->getData('jcommFirstStatus');
        if ($config->isResetAdminPasswordEnabledForAdmin() && $sendEmail == 'STATUS_NEW') {
            $registration = new \Jelix\JCommunity\Registration();
            try {
                $registration->createUserByAdmin($event->user);
            } catch(\phpmailerException $e) {
                \jLog::logEx($e, 'error');
                jMessage::add(jLocale::get('jcommunity~password.form.change.error.smtperror'), 'error');
                return;
            }
            jMessage::add(jLocale::get('jcommunity~account.form.admin.create.emailsent'));
        }
    }
}
