<?php
/**
 * @package     jelix
 * @subpackage  core_request
 *
 * @author      Laurent Jouanneau
 * @copyright   2005-2011 Laurent Jouanneau
 *
 * @see        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * the jJsonRpcRequest require jJsonRpc class.
 */
require JELIX_LIB_UTILS_PATH.'jJsonRpc.class.php';

/**
 * handle a JSON-rpc call. The response has to be a json rpc response.
 *
 * @package  jelix
 * @subpackage core_request
 */
class jJsonRpcRequest extends jRequest
{
    public $type = 'jsonrpc';

    public $defaultResponseType = 'jsonrpc';

    public $authorizedResponseClass = 'jResponseJsonrpc';

    public $jsonRequestId;

    /**
     * Analyze the HTTP request and set the params property.
     */
    protected function _initParams()
    {
        $request = file_get_contents('php://input');

        // Decode the request
        $requestobj = jJsonRpc::decodeRequest($request);
        if ($requestobj['method']) {
            list($module, $action) = explode('~', $requestobj['method']);
        } else {
            $module = '';
            $action = '';
        }
        if (isset($requestobj['id'])) {
            $this->jsonRequestId = $requestobj['id'];
        }

        if (is_array($requestobj['params'])) {
            $this->params = $requestobj['params'];
        }

        $this->params['params'] = $requestobj['params'];

        // Definition of action to use and its parameters
        $this->params['module'] = $module;
        $this->params['action'] = $action;
    }
}
