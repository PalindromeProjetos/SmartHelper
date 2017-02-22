<?php

namespace SmartFull\Data\Traits;

trait Annotations
{
    private $_notate = null;

    private $notation = array(
        'instance','property','function'
    );

	private static $HttpMethods = array(
		'HttpGet','HttpPut','HttpPost','HttpDelete','HttpOptions','HttpHead'
	);

    public function getNotate()
    {
        return $this->_notate;
    }

    private function setNotation(array $notation)
    {
        $this->notation = $notation;
    }

    private function getAnnotations($className) {
        $annotation = new \stdClass();

        foreach ($this->notation as $type=>$data) {
            $method = 'getAnnotations' . strtoupper($type[0]) . substr($type, 1);
            $annotation->$type = $this->$method($className);
        }

        return $annotation;
    }

    private function getAnnotationsInstance($className) {
        $annotation = [];
        $reflection = new \ReflectionClass($className);

        preg_match_all('(\*\s*@.*\{[^{}]+\})', $reflection->getDocComment(), $comments);

        foreach ($this->notation['instance'] as $key) {
            foreach ($comments[0] as $index=>$text) {
                if ( preg_match("/@$key/", $text) ) {
                    $params = trim(preg_replace("/@$key|\*/", '', $text));
					$annotation['Name'] = '\\'.$reflection->getName();
                    $annotation[$key] = json_decode($params);
                }
            }
        }

        return $annotation;
    }

    private function getAnnotationsProperty($className) {
        $annotation = [];
        $class = new \ReflectionClass($className);
        $props = $class->getProperties();

        foreach ($props as $table=>$field) {
            $reflection = new \ReflectionProperty($field->class, $field->name);

            preg_match_all('(\*\s*@.*\{[^{}]+\})', $reflection->getDocComment(), $comments);

            foreach ($this->notation['property'] as $key) {
                foreach ($comments[0] as $index=>$text) {
                    if ( preg_match("/@$key/", $text) ) {
                        $params = trim(preg_replace("/@$key|\*/", '', $text));
                        $annotation[$field->name][$key] = json_decode($params);
                    }
                }
            }
        }

        return $annotation;
    }

    private function getAnnotationsFunction($className) {
        $annotation = [];
        $class = new \ReflectionClass($className);
        $props = $class->getMethods();
		
        foreach ($props as $table=>$function) {
            $i = 0;
            $arg = 'args';
			$reflection = new \ReflectionMethod($function->class, $function->getName());
            $parameters = $function->getParameters();
			
            foreach ($parameters as $param) {
                $parameters[$i]->defaultValue = null;
                if($param->isDefaultValueAvailable()) {
                    $parameters[$i]->defaultValue = $param->getDefaultValue();
                    $i++;
                }
            }

            preg_match_all('(\*\s*@.*\{[\w\W]+\})', $reflection->getDocComment(), $comments);

			foreach ($this->notation['function'] as $key) {
				foreach ($comments[0] as $index=>$text) {
					if ( preg_match("/@$key/", $text) ) {
						$params = trim(preg_replace("/@$key|\*/", '', $text));
						$annotation[$function->name][$arg] = $parameters;
						$annotation[$function->name][$key] = json_decode($params);
					}
				}
			}
        }

        return $annotation;
    }

    private function getAnnotationsArgument($className,$method) {
        $annotation = [];
        $class = new \ReflectionClass($className);
        $props = $class->getMethods();

        foreach ($props as $function) {
            if( $function->name == $method) {
                $parameters = $function->getParameters();
                $annotation[$function->name] = $parameters;
            }
        }

        return $annotation;
    }

}