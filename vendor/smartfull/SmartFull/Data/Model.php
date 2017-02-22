<?php

namespace SmartFull\Data;

use SmartFull\Utils\ObjectBase;
use SmartFull\Data\Traits\ArrayTools;
use SmartFull\Data\Traits\Annotations;
use SmartFull\Data\Interfaces\ModelInterface;
use SmartFull\Interfaces\ObjectBaseInterface;
use SmartFull\Data\Interfaces\PolicyFieldInterface;

class Model extends ObjectBase implements ObjectBaseInterface, ModelInterface
{
    use Annotations;

    public function __construct(\stdClass $entity = null)
    {

        $this->setNotation(array(
            'instance'=>array('Entity'),
            'property'=>array('Policy','Column')
        ));

        $this->_notate = $this->getAnnotations($this);

		if ($entity == null) { return; }
			
		if ($entity instanceof \stdClass) {
			$this->hydrateModel($entity);
		}

	}

	public function __toString() : string {
		$entity = array();
		$fields = $this->_notate->property;

        foreach ($fields as $field=>$value) {
			$entity[$field] = $this->$field;
        }

		return self::arrayToJson($entity);
	}

	public function toArray () : array {
		$entity = array();
		$fields = $this->_notate->property;

		foreach ($fields as $field=>$value) {
			if(strlen($this->$field) != 0) {
				$entity[$field] = $this->$field;
			}
		}

		return $entity;
	}

	public function hydrateModel(\stdClass $entity = null) {

		// TODO: Implement Policy fields
		if ($entity == null) { return; }

		if ($entity instanceof \stdClass) {
			foreach ($entity as $field=>$value) {
				if(property_exists($this, $field)) {
					$this->$field = $value;
				}
			}			
		}

	}

}
/*
array (size=1)
  'id' => 
    array (size=2)
      'Policy' => 
        object(stdClass)[4]
          public 'nullable' => boolean false
      'Column' => 
        object(stdClass)[5]
          public 'description' => string '' (length=0)
          public 'strategy' => string 'AUTO' (length=4)
          public 'type' => string 'integer' (length=7)
          public 'policy' => boolean false
          public 'logallow' => boolean true
          public 'default' => string '' (length=0)
*/