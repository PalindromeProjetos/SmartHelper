<?php

namespace SmartFull\Utils;

use SmartFull\Interfaces\ObjectBaseInterface;

class ObjectBase implements ObjectBaseInterface
{

    public function __get($field) {
        if (property_exists($this, $field)) {
            return $this->$field;
        } else {
            throw new Exception(self::UNKNOWN_PROPERTY);
        }
    }

    public function __set($field, $value) {
        if (property_exists($this, $field)) {
            $this->$field = $value;
        } else {
            throw new Exception(self::UNKNOWN_PROPERTY);
        }
        return $this;
    }

}