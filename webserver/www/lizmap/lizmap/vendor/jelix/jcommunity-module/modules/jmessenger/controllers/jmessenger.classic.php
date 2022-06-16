<?php
/**
* @package      jcommunity
* @subpackage   jmessenger
* @author       Bastien Jaillot <bastnicj@gmail.com>
* @contributor
* @copyright    2008 Bastien Jaillot
* @link         http://bitbucket.org/laurentj/jcommunity/
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class jmessengerCtrl extends jControllerDaoCrud {

    protected $dao = 'jmessenger~jmessenger';
    protected $form = 'jmessenger~jmessenger';
    protected $viewTemplate = 'jmessenger~view';
    protected $editTemplate = 'jmessenger~new';
    protected $listTemplate = 'jmessenger~listmsg';
    protected $dbProfil = '';

    function index() {
        $resp = $this->getResponse('redirect');
        $resp->action = "jmessenger~jmessenger:inbox";
        return $resp;
    }

    function inbox(){
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', jZone::get('inbox'));
        return $rep;
    }

    function outbox(){
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', jZone::get('outbox'));
        return $rep;
    }

    protected function _beforeSaveCreate($form, $form_daorec){
        $form_daorec->id_from = jAuth::getUserSession()->id;
        $form_daorec->isSeen = 0;
        $form_daorec->isArchived = 0;
        $form_daorec->isReceived = 1;
        $form_daorec->isSend = 1;
        if ($form_daorec->id_for == 0)
            $form_daorec->id_for = $form->getData("answer_to");
    }

    function answer() {
        $resp = $this->precreate();
        $this->_markAsRead();

        if ($id = $this->param("id")) {
            // verify i'm the dest of the message i will answer
            $mail = jDao::get($this->dao)->get($id);
            if ($mail->id_for != jAuth::getUserSession()->id)
                return $resp;

            $form = jForms::get($this->form);
            if($form == null){
                $form = jForms::create($this->form);
            }

            $form->deactivate("id_for", true);
            $form->setData("answer_to", $mail->id_from);
            $resp->params['id'] = $id;

            $form->setData("title", "Re : ".jDao::get($this->dao)->get($id)->title);
        }
        return $resp;
    }

    protected function _view($form, $resp, $tpl){
        $this->_markAsRead();
    }

    protected function _markAsRead() {
        $dao = jDao::get($this->dao);
        $m = $dao->get($this->param("id",0));
        if ($m && $m->id_for == jAuth::getUserSession()->id) {
            $m->isSeen = 1;
            $dao->update($m);
        }
    }

    protected function _afterCreate($form, $id, $resp) {
        $msg = array();
        $msg['id'] = $id;
        $msg['user_id_from'] = jAuth::getUserSession()->id;
        $msg['user_id_to'] = $form->getData('id_for');
        $msg['title'] = $form->getData('title');
        $msg['content'] = $form->getData('content');
        jEvent::notify('jmessengerMessageSent', array('message'=>$msg));
    }
}
