<?php

/**
 * @package     jelix-modules
 * @subpackage  jauth
 *
 * @author      Laurent Jouanneau
 * @contributor Antoine Detante, Bastien Jaillot, Loic Mathaud, Vincent Viaud, Julien Issler
 *
 * @copyright   2005-2022 Laurent Jouanneau, 2007 Antoine Detante, 2008 Bastien Jaillot
 * @copyright   2008 Loic Mathaud, 2011 Vincent Viaud, 2015 Julien Issler
 *
 * @see        http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class loginCtrl extends jController
{
    public $sensitiveParameters = array('password');

    public $pluginParams = array(
        '*' => array('auth.required' => false),
    );

    public function in()
    {
        $conf = jAuth::loadConfig();

        // both after_login and after_logout config fields are required
        if ($conf['after_login'] == '') {
            throw new jException('jauth~autherror.no.after_login');
        }

        if ($conf['after_logout'] == '') {
            throw new jException('jauth~autherror.no.after_logout');
        }

        $rep = $this->getResponse('redirectUrl');
        if (!jAuth::login(
            $this->param('login'),
            $this->param('password'),
            $this->param('rememberMe')
        )) {
            // auth fails
            sleep(intval($conf['on_error_sleep']));
            $params = array('login' => $this->param('login'), 'failed' => 1);
            if ($conf['enable_after_login_override']) {
                $url = $this->param('auth_url_return');
                if (jAuth::checkReturnUrl($url)) {
                    $params['auth_url_return'] = $url;
                }
            }
            $rep->url = jUrl::get($conf['after_logout'], $params);
        } else {
            if ($conf['enable_after_login_override']) {
                $url_return = $this->param('auth_url_return');
                if (jAuth::checkReturnUrl($url_return)) {
                    $rep->url = $url_return;
                } else {
                    $rep->url = jUrl::get($conf['after_login']);
                }
            } else {
                $rep->url = jUrl::get($conf['after_login']);
            }
        }

        return $rep;
    }

    public function out()
    {
        $rep = $this->getResponse('redirectUrl');
        jAuth::logout();
        $conf = jAuth::loadConfig();

        if ($conf['after_logout'] == '') {
            throw new jException('jauth~autherror.no.after_logout');
        }

        if (jApp::coord()->execOriginalAction()) {
            $url_return = $this->param('auth_url_return');
            if ($conf['enable_after_logout_override'] && jAuth::checkReturnUrl($url_return)) {
                $rep->url = $url_return;
            } else {
                $rep->url = jUrl::get($conf['after_logout']);
            }
        } else {
            // we are here because of an internal redirection (authentication missing)
            // if we can indicate the url to go after the login, let's pass this url
            // to the next action (which is in most of case a login form)
            if ($conf['enable_after_login_override'] && $_SERVER['REQUEST_METHOD'] == 'GET') {
                $rep->url = jUrl::get(
                    $conf['after_logout'],
                    array('auth_url_return' => jUrl::getCurrentUrl())
                );
            } else {
                $rep->url = jUrl::get($conf['after_logout']);
            }
        }

        return $rep;
    }

    /**
     * Shows the login form.
     */
    public function form()
    {
        $conf = jAuth::loadConfig();
        if (jAuth::isConnected()) {
            if ($conf['after_login'] != '' && $conf['after_login'] != 'jauth~login:form') {
                $url_return = $this->param('auth_url_return');
                if (!($conf['enable_after_login_override']
                    && jAuth::checkReturnUrl($url_return))) {
                    $url_return = jUrl::get($conf['after_login']);
                }

                return $this->redirectToUrl($url_return);
            }
        }

        $rep = $this->getResponse('htmlauth');
        $rep->title = jLocale::get('auth.titlePage.login');
        $rep->bodyTpl = 'jauth~index';

        $zp = array(
            'login' => $this->param('login', ''),
            'failed' => $this->param('failed'),
            'showRememberMe' => jAuth::isPersistant(),
        );

        if ($conf['enable_after_login_override']) {
            $url_return = $this->param('auth_url_return');
            if (jAuth::checkReturnUrl($url_return)) {
                $zp['auth_url_return'] = $url_return;
            }
        }

        $rep->body->assignZone('MAIN', 'jauth~loginform', $zp);

        return $rep;
    }
}
