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


class archiveZone extends jZone {

    protected $_tplname = "jmessenger~listmsg";
    protected $dao = "jmessenger~message";
    protected $_tplOuputType = "html";

    protected function _prepareTpl(){
        $id = $this->param("id", jAuth::getUserSession()->id);
        $title = jLocale::get("jmessenger~message.msg.archived");
        
        $dao = jDao::get($this->dao);
        $msg = $dao->getArchive($id);
        $send = true;

        $this->_tpl->assign(compact('msg', 'id', 'title', 'send'));
    }
}

