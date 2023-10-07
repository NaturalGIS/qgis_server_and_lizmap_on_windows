<?php
/**
* @package      jcommunity
* @subpackage
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2007-2023 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class registrationZone extends jZone {

   protected $_tplname='registration';


    protected function _prepareTpl()
    {
        list($form, $passWidget) = \Jelix\JCommunity\FormPassword::getFormAndWidget('registration', 'reg_password');
        $this->_tpl->assign('passwordWidget', $passWidget);
        jEvent::notify('jcommunity_registration_init_form', array('form'=>$form,'tpl'=>$this->_tpl) );
        $this->_tpl->assign('form',$form);
    }

}
