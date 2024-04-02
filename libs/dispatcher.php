<?php

declare(strict_types=1);
/**
 * End User License Agreement ("EULA") *
 *
 * This End User License Agreement ("EULA") governs the use of the property software ("Software") provided by [Software Owner], * hereinafter referred to as the "Owner."
 * 1. The Owner hereby grants you a non-exclusive, non-transferable license to use the Software in accordance with the terms and conditions set forth in this EULA.
 * 2. You may not copy, modify, distribute, sell, sublicense, or reverse engineer the Software. You may not use the Software for any illegal or unauthorized purpose.
 * 3. The Software is the property of the Owner and is protected by copyright laws and international treaty provisions. You acknowledge that you have no ownership rights to the Software.
 * The Software is provided "as is," without any warranties or guarantees of any kind. The Owner disclaims all warranties, express or implied, including but not limited to the implied warranties of merchantability and fitness for a particular purpose.
 * 5. In no event shall the Owner be liable for any damages, including but not limited to direct, indirect, incidental, special, or consequential damages arising out of the use or inability to use the Software, even if the Owner has been advised of the possibility of such damages.
 * 6. You agree to indemnify and hold harmless the Owner from any claims, damages, liabilities, costs, or expenses arising out of your use of the Software in violation of this EULA.
 * This EULA shall be governed by and construed in accordance with the laws of [Jurisdiction]. Any disputes arising under or in connection with this EULA shall be subject to the exclusive jurisdiction of the courts of [Jurisdiction].
 * 8. This EULA constitutes the entire agreement between you and the Owner regarding the use of the Software and supersedes all prior or contemporaneous agreements and understandings, whether oral or written.
 * 9. If any provision of this EULA is found to be invalid or unenforceable, the remaining provisions shall remain in full force and effect.
 * 10. By installing, copying, or otherwise using the Software, you acknowledge that you have read, understood, and agreed to be bound by the terms and conditions of this EULA.
 *
 * Written By Akanksha Adhikary(LADYHART)
 *
 * @contact: ladyhart@protonmail.com
 * @date: March 28, 2024
 * @copyright     Copyright (c) Akanksha Adhikary (https://ladyhart.org)
 * @link          https://ladyhart.in/
 * @since         1.1.6
 * @license       https://ladyhart.org/licenses/own GPL3 License
 *
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 */

use HTTPRequest as Request;

class Dispatcher extends Session
{
    private $_environ;
    private $_config;
    private $_request;
    private $_response;
    private $_view;
    private $_headers;
    private $_controller;
    private $_action;
    private $_vars;
    private $_timePeriod;
    private $__props = [
        '_environ',
        '_headers',
        '_timePeriod',
        '_request',
    ];
    public static $httpMethod = "GET";

    public function __construct($req, $res)
    {
        parent::__construct(get_class($this));
        $this->_environ = Environment::Load(ENV_FILE);
        $this->_request = $req;
        $this->_response = $res;
        $this->_view = null;
        $this->_headers = $req->headers;
        $this->_controller = null;
        $this->_timePeriod = time();
        self::$httpMethod = Request::getHttpMethod();
    }
    public function __destruct()
    {
        //ob_clean();
        try {
            $this->_response->render(
                $this->_view,
                $this->_headers
            );
        } catch (RuntimeException $e) {
            throw new ServiceNotFoundException();
        }
        //reset all handdles
    }
    public function __get($name)
    {
        $_name = '_' . lcfirst($name);
        if (in_array($_name, $this->__props)) {
            return $this->{$_name};
        }
        return null;
    }

    public static function getSession(Session $dispatcher)
    {
        return $dispatcher;
    }

    public function getPath()
    {
        return rawurldecode(rtrim($this->_request . '', ENV_PHP_EXT));
    }
    public function dispatch($class, $dispatcher)
    {

        if (class_exists($class)) {
            try {
                $this->_controller = new $class($this);
                $routeInfo = $dispatcher->dispatch(self::$httpMethod, $this->getPath());
                $this->_action = $routeInfo[1];
                $this->_vars = $routeInfo[2];
            } catch (Exception $e) {
                throw new ServiceNotFoundException();
            }
            if (!method_exists($this->_controller, $this->_action)) {
                $this->_action = null;
                $this->_controller = null;
                throw new MethodNotAllowedException();
            }
            $this->_headers = array('Connection' => 'close', 'Cache-Control' => 'no-cache', 'Content-type' => "text/html; charset=UTF-8");
            return $this;
        }
        throw new ServiceNotFoundException();
    }
    private function _attach($action, $vars)
    {
        $names = array();
        try {
            $ref = new ReflectionMethod($this->_controller, $action);
            $params = $ref->getParameters();
            foreach ($params as $param) {
                array_push($names, $param->getName());
            }
        } finally {
            $args = array();
            foreach ($names as $name) {
                if (isset($this->_attached[$name])) {
                    $args[$name] = $this->_attached[$name];
                } else {
                    throw new InvalidArgumentException();
                }
            }
            return call_user_func_array([$this->_controller, $action], $args);
        }
        throw new MethodNotAllowedException();
    }
    private function _begin($vars)
    {
        if (isset($vars[0]) && count($vars) == 1) {
            $vars = $vars[0];
        }
        if ($vars instanceof WeakMap) {
            $this->_attached = $vars->dump();
        } else if (is_array($vars)) {
            $this->_attached = $vars;
        }
        return $this;
    }
    public function event(string $handler, ...$vars)
    {
        switch ($handler) {
            case 'begin':
                return $this->_begin($vars);
                break;
            case 'shutdown':
                break;
            default:
                if (is_string($this->_action) && $this->_action == $handler) {

                    $this->_view = mb_convert_encoding($this->_attach($this->_action, $this->_vars) . '', 'UTF-8');
                    return $this;
                }
                throw new BadMethodCallException("Method $handler does not exist");
        }
        return $this;
    }
    public function __call($handler, $vars)
    {
        throw new BadMethodCallException("Method $handler does not exist");
    }

    public function cache($a)
    {
        //ob_clean();
        return $this;
    }
    public function end(bool $render = true)
    {
        if ($render & !is_null($this->_view)) {
            return $this->_view;
        }
        $this->_response->stop($this->_headers);
        throw new ServiceNotFoundException("Server returned an empty response");
    }
}
