<?php

/**
 * @package    jelix
 * @subpackage core
 *
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud
 *
 * @copyright   2005-2023 Laurent Jouanneau, 2006 Loic Mathaud
 *
 * @see        http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * interface for controllers used for RESTFull request/response.
 *
 * @package  jelix
 * @subpackage core
 */
interface jIRestController
{
    public function get();

    public function post();

    public function put();

    public function delete();
}

/**
 * class base for controllers.
 *
 * A controller is used to implement one or many actions, one method for each action.
 *
 * @package  jelix
 * @subpackage core
 */
abstract class jController
{
    /**
     * parameters for plugins of the coordinator.
     *
     * this array should contains all parameters needed by installed plugins for
     * each action, see the documentation of each plugins to know this parameters.
     * keys : name of an action or * for parameters to all action
     * values : array that contains all plugin parameters
     *
     * @var array
     */
    public $pluginParams = array();

    /**
     * sensitive parameters.
     *
     * List of names of parameters that can have sensitive values like password etc.
     * This list is used by the logger for example, to replace values by a dummy value.
     * See also sensitiveParameters into error_handling section of the configuration.
     *
     * @var string[]
     *
     * @since 1.6.16
     */
    public $sensitiveParameters = array();

    /**
     * the request object.
     *
     * @var jRequest
     */
    protected $request;

    /**
     * @param jRequest $request the current request object
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Gets the value of a request parameter. If not defined, gets its default value.
     *
     * @param string $parName           the name of the request parameter
     * @param mixed  $parDefaultValue   the default returned value if the parameter doesn't exists
     * @param bool   $useDefaultIfEmpty true: says to return the default value if the parameter value is ""
     *
     * @return mixed the request parameter value
     */
    protected function param($parName, $parDefaultValue = null, $useDefaultIfEmpty = false)
    {
        return $this->request->getParam($parName, $parDefaultValue, $useDefaultIfEmpty);
    }

    /**
     * same as param(), but convert the value to an integer value. If it isn't
     * a numerical value, return null.
     *
     * @param string $parName           the name of the request parameter
     * @param mixed  $parDefaultValue   the default returned value if the parameter doesn't exists
     * @param bool   $useDefaultIfEmpty true: says to return the default value the value is ""
     *
     * @return int the request parameter value
     */
    protected function intParam($parName, $parDefaultValue = null, $useDefaultIfEmpty = false)
    {
        $value = $this->request->getParam($parName, $parDefaultValue, $useDefaultIfEmpty);
        if (is_numeric($value)) {
            return intval($value);
        }

        return null;
    }

    /**
     * same as param(), but convert the value to a float value. If it isn't
     * a numerical value, return null.
     *
     * @param string $parName           the name of the request parameter
     * @param mixed  $parDefaultValue   the default returned value if the parameter doesn't exists
     * @param bool   $useDefaultIfEmpty true: says to return the default value the value is ""
     *
     * @return float the request parameter value
     */
    protected function floatParam($parName, $parDefaultValue = null, $useDefaultIfEmpty = false)
    {
        $value = $this->request->getParam($parName, $parDefaultValue, $useDefaultIfEmpty);
        if (is_numeric($value)) {
            return floatval($value);
        }

        return null;
    }

    /**
     * same as param(), but convert the value to a boolean value. If it isn't
     * a numerical value, return null.
     *
     * @param string $parName           the name of the request parameter
     * @param mixed  $parDefaultValue   the default returned value if the parameter doesn't exists
     * @param bool   $useDefaultIfEmpty true: says to return the default value the value is ""
     *
     * @return bool the request parameter value
     */
    protected function boolParam($parName, $parDefaultValue = null, $useDefaultIfEmpty = false)
    {
        $value = $this->request->getParam($parName, $parDefaultValue, $useDefaultIfEmpty);
        if ($value == 'true' || $value == '1' || $value == 'on' || $value == 'yes') {
            return true;
        }
        if ($value == 'false' || $value == '0' || $value == 'off' || $value == 'no') {
            return false;
        }

        return null;
    }

    /**
     * @return array all request parameters
     */
    protected function params()
    {
        return $this->request->params;
    }

    /**
     * get a response object.
     *
     * @param string $name        the name of the response type (ex: "html")
     * @param bool   $useOriginal true:don't use the response object redefined by the application
     *
     * @return jResponse|jResponseHtml|jResponseJson|jResponseRedirect the response object
     */
    protected function getResponse($name = '', $useOriginal = false)
    {
        return $this->request->getResponse($name, $useOriginal);
    }

    /**
     * get a jReponseRedirectUrl object
     * @param string $url  the url
     * @return \jResponseRedirectUrl
     */
    protected function redirectToUrl($url, $temporary = true)
    {
        /** @var \jResponseRedirectUrl $response */
        $response = $this->request->getResponse('redirectUrl');
        $response->url = $url;
        $response->temporary = $temporary;
        return $response;
    }

    /**
     * get a jReponseRedirect object
     * @param string $action  the action selector, like "mymodule~myctrl:mymethod"
     * @param array $parameters  parameters of the action
     * @param bool $temporary temporary redirection (true) or permanent redirection (false)
     * @param string $anchor  url anchor
     * @return \jResponseRedirect
     */
    protected function redirect($action, $parameters = [], $anchor = '', $temporary = true)
    {
        /** @var \jResponseRedirect $response */
        $response = $this->request->getResponse('redirect');
        $response->action = $action;
        $response->params = $parameters;
        $response->temporary = $temporary;
        $response->anchor = $anchor;
        return $response;
    }

    /**
     * Return the given file as a response.
     *
     * It reads the file content and will return it into the HTTP Response.
     * Mimetype will be set. Can use HTTP cache optionally.
     *
     * Returns a 404 response if the file does not exists.
     *
     * @param string $filename path to the file
     * @param bool $useCache true if http cache must be activated, based on the
     * date of the file.
     * @return jResponseBinary|jResponseHtml
     */
    protected function getFileResponse($filename, $useCache = true)
    {
        if (!is_file($filename)) {
            $rep = $this->getResponse('html', true);
            $rep->bodyTpl = 'jelix~404.html';
            $rep->setHttpStatus('404', 'Not Found');

            return $rep;
        }

        $rep = $this->getResponse('binary');

        if ($useCache) {
            $dateModif = new DateTime();
            $dateModif->setTimestamp(filemtime($filename));
            if ($rep->isValidCache($dateModif)) {
                return $rep;
            }
        }

        $rep->doDownload = false;
        $rep->fileName = $filename;
        $rep->mimeType = \Jelix\FileUtilities\File::getMimeTypeFromFilename($rep->fileName);

        return $rep;
    }
}
