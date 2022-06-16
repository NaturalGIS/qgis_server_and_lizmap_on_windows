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


class inboxZone extends jZone {

    protected $_tplname = "jmessenger~listmsg";
    protected $dao = "jmessenger~jmessenger";

    protected function _prepareTpl(){
        $id = jAuth::getUserSession()->id;
        $title = jLocale::get("jmessenger~message.msg.inbox");
        
        $dao = jDao::get($this->dao);
        $msg = $dao->getRecus($id);

        $this->_tpl->assign(compact('msg', 'id', 'title'));
    }
}

