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


class jmessenger {

    function getNewMessage() {
        $dao = jDao::get("jmessenger~jmessenger");
        $nb = $dao->getNbNewMessage(jAuth::getUserSession()->id);
        return $nb;
    }

    function getLibelleNewMessage() {
        $nb = $this->getNewMessage();
        switch ($nb) {
            case 0:
                $libelle = jLocale::get("jmessenger~message.count0");
                break;
            case 1:
                $libelle = jLocale::get("jmessenger~message.count1");
                break;
            default:
                $libelle = jLocale::get("jmessenger~message.countmore", array($nb));
                break;
        }
        return $libelle;
    }
}
