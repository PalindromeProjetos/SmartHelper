<?php

namespace SmartFull;

use SmartFull\Data\MappingRouter;
use SmartFull\Data\Traits\ArrayTools;
use SmartFull\Data\Traits\Annotations;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class App extends \Slim\App
{
	use ArrayTools;
	use Annotations;

    const VERSION = '2.0.0';

	private static $controllers = null;

	public function __construct($container = [])
    {
        $i = 0;
		parent::__construct($container);

		$classes = $this->getControllers($container['settings']['apiControllers']);

		$this->setNotation(array(
			'instance'=>array('RoutePrefix'),
			'function'=>self::$HttpMethods
		));

		foreach ($classes as $controller) {
			$this->_notate[$i] = $this->getAnnotations($controller);
            $i++;
		}

		self::$controllers = new MappingRouter($this->_notate, self::$HttpMethods);
    }

	private function getControllers($path) {
		$fqcns    = array();
		$allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
		$phpFiles = new \RegexIterator($allFiles, '/\.php$/');

		foreach ($phpFiles as $phpFile) {
			$namespace = '';
			$content   = file_get_contents($phpFile->getRealPath());
			$tokens    = token_get_all($content);
			
			for ($index = 0; isset($tokens[$index]); $index++) {
				
				if (!isset($tokens[$index][0])) {
					continue;
				}
				
				if (T_NAMESPACE === $tokens[$index][0]) {
					$index += 2; // Skip namespace keyword and whitespace
					while (isset($tokens[$index]) && is_array($tokens[$index])) {
						$namespace .= $tokens[$index++][1];
					}
				}
				
				if (T_CLASS === $tokens[$index][0]) {
					$index += 2; // Skip class keyword and whitespace
					$fqcns[] = $namespace.'\\'.$tokens[$index][1];
				}
			}
		}
		
		return $fqcns;
	}

    public function run($silent = false) {
        $request = $this->getContainer()->get('request');
        $http    = strtolower($request->getMethod());
        $path    = $request->getUri()->getPath();

        $this->$http("/$path", function (Request $request, Response $response) {
            $args    = array();

            self::$controllers->hasRoutesConflict();

            $mapping = self::$controllers->hydratedRoute($request);

            $class   = $mapping->instance['Name'];
            $route   = $mapping->function['route']['name'];
            $param   = $mapping->function[$route];

            $controller = new $class($request,$response);

            // Hidrata parametros da rota ( defaultValue )
            foreach ($param as $item) {
                $args[$item->name] = isset($item->defaultValue) ? $item->defaultValue : "";
            }

            $result = call_user_func_array(array($controller, $route), $args);

            return $response->withStatus($result->getStatus())->write($result);
        });

        parent::run($silent);
    }

}