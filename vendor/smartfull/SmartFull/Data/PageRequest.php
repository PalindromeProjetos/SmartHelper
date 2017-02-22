<?php

namespace SmartFull\Data;

class PageRequest extends \ArrayObject
{

    public function getStart () {
        $index = isset($this['start']) ? $this['start']+1 : 1;
        
        return $index;
    }

    public function getLimit () {
        $start = isset($this['start']) ? $this['start'] : 0;
        $limit = isset($this['limit']) ? $this['limit'] : 10;

        return ($start+$limit);
    }

}