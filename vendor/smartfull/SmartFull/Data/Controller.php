<?php

namespace SmartFull\Data;

use SmartFull\Data\Auth;
use SmartFull\Data\Traits\Annotations;
use SmartFull\Data\Interfaces\ControllerInterface;
use SmartFull\Data\Traits\ArrayTools;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Controller implements ControllerInterface
{
    use ArrayTools;
    use Annotations;

    private $request;
    private $response;

    public function __construct(?Request &$request, ?Response &$response)
    {
        $this->setNotation(array(
            'instance'=>array('RoutePrefix'),
			'function'=>self::$HttpMethods
        ));

        $this->_notate = $this->getAnnotations($this);

        if(!($request instanceof Request)) { return; }

        if(!($response instanceof Response)) { return; }

        $this->request = $request;
        $this->response = $response;

        $auth = $this->request->getHeader('Authorization');
        //"users/auth"

    }

    public function getRoutes() : array {
        $i = 0;
        $routes = array();
        $routePrefix = $this->_notate->instance['RoutePrefix']->name;

        foreach (self::$HttpMethods as $http) {
            foreach ($this->_notate->function as $methods) {
                if(isset($methods[$http])) {
                    $routes[$i] = $http .'.'. $routePrefix .'.'. $methods[$http]->route;
                    $i++;
                }
            }
        }

        return $routes;
    }

    public function getRequest(): Request {
        return $this->request;
    }

    public function getResponse(): Response {
        return $this->response;
    }

}