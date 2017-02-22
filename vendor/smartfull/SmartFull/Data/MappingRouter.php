<?php

namespace SmartFull\Data;

use SmartFull\Data\Model;
use SmartFull\Data\Traits\ArrayTools;
use Psr\Http\Message\ServerRequestInterface;
use SmartFull\Data\Interfaces\ControllerInterface;
use SmartFull\Data\Interfaces\MappingRouterInterface;

class MappingRouter implements MappingRouterInterface
{
    use ArrayTools;

    protected $mapping = null;

    public function __construct(array $router, array $httpMethods) {
        $this->setMapping($router, $httpMethods);
    }

    /**
     * @return null
     */
    public function getMapping() {
        return $this->mapping;
    }

    /**
     * @param array $mapping
     * @param array $httpMethods
     * @return $this
     */
    public function setMapping(array $mapping, array $httpMethods) {
        $i = 0;

        $map = array();

        foreach ($mapping as $item) {

            $function = $item->function;

            foreach ($httpMethods as $http) {

                $j = 0;

                foreach ($function as $key => $val) {

                    if(isset($function[$key][$http])) {
                        $prefix = $item->instance['RoutePrefix']->name;
                        $route = $prefix . '/' . $function[$key][$http]->route;

                        $map[$i]['instance'] = $item->instance;
                        $map[$i]['function'][$http][$j]['route']['name'] = $key;
                        $map[$i]['function'][$http][$j]['route']['path'] = $route;
                        $map[$i]['function'][$http][$j][$key] = $function[$key]['args'];
                    }

                    $j++;

                }

            }

            $i++;
        }

        $this->mapping = $map;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function hydratedRoute(ServerRequestInterface $request) {
        $mapping = $this->mapRouter($request);
        $path    = $mapping->function['route']['path'];
        $name    = $mapping->function['route']['name'];

        $target = explode('/',$path);
        $source = explode('/',$request->getUri()->getPath());

        $q = 0;

        if($request->isGet()) {
            $query   = $request->getQueryParams();
            foreach ($mapping->function[$name] as $data) {

                if(isset($query[$data->getName()])) {
                    $data->defaultValue = $query[$data->getName()];
                }

                $mapping->function[$name][$q] = $data;
                $q++;
            }
        }

        if($request->isPost() || $request->isPut()) {
            $body   = $request->getParsedBody();
            foreach ($mapping->function[$name] as $param) {

                if(isset($body[$param->getName()])) {
                    $modelClass = $param->getClass()->getName();
                    $bodyClass = self::jsonTryToObject($body[$param->getName()]);
                    $param->defaultValue = new $modelClass($bodyClass);
                }

                $mapping->function[$name][$q] = $param;
                $q++;
            }
        }

        $i = 0;

        foreach ($target as $item) {

            if(preg_match("/.*\{.+\}.*/", $item)) {
                $value = $source[$i];
                $field = preg_replace("/\{|\}/", "", $item);

                $j = 0;

                foreach ($mapping->function[$name] as $data) {

                    if($data->getName() == $field) {
                        $data->defaultValue = $value;
                    }

                    $mapping->function[$name][$j] = $data;
                    $j++;
                }
            }

            $i++;
        }

        return $mapping;
    }

    /**
     * @param ServerRequestInterface $request
     * @return \stdClass
     */
    public function mapRouter(ServerRequestInterface $request) {
        $mapping     = new \stdClass();
        $path        = $request->getUri()->getPath();
        $method      = strtolower($request->getMethod());
        $HttpMethod  = "Http" . strtoupper($method[0]) . substr($method, 1);
        $routeSource = preg_replace("/\{\w+\}|\d+/", "_smartfull_", $path);

        foreach ($this->mapping as $item) {

            $routeList = isset($item['function'][$HttpMethod]) ? $item['function'][$HttpMethod] : [];

            foreach ($routeList as $routeItem) {

                $route = $routeItem['route']['path'];
                $routeTarget = preg_replace("/\{\w+\}|\d+/", "_smartfull_", $route);

                if(rtrim($routeSource,'/') == rtrim($routeTarget,'/')) {
                    $mapping->instance = $item['instance'];
                    $mapping->function = $routeItem;
                }

            }

        }

        return $mapping;
    }

    /**
     * @return array
     */
    public function getRoutes() : array {
        $route = array();
        $a = $b = null;

        foreach ($this->mapping as $controller) {
            $class = new $controller['instance']['Name']($a,$b);
            $route  = array_merge($route,$class->getRoutes());
        }

        return $route;
    }

    public function hasRoutesConflict(){
        $routes_all = $this->getRoutes();
        $routes_new = self::uniqueArray($routes_all);

        if(count($routes_all) != count($routes_new)) {
            throw new \Exception('Existem conflitos nas rotas registradas!');
        }
    }

}