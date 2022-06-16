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


class linksZone extends jZone {

    protected $_tplname = "jmessenger~links";

    protected function _prepareTpl(){
        if(jAuth::isConnected()) {
            $this->_tpl->assign('login',jAuth::getUserSession ()->login);
        }
    }
}

