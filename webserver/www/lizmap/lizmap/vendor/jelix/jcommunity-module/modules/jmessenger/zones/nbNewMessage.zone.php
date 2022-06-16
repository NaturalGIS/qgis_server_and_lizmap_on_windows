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


class nbNewMessageZone extends jZone {

    protected $_tplname = "jmessenger~nbNewMessage";

    protected function _prepareTpl(){
        $this->_tpl->assign('nb', jClasses::getService("jmessenger~jmessenger")->getLibelleNewMessage());
    }
}

