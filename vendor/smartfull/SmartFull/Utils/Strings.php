<?php

namespace SmartFull\Utils;

class Strings extends \SmartFull\Utils\ObjectBase
{

    public $value = "";

    public function __construct($value) {

        $this->value = $value;
    }

    public function __invoke() {
        echo $this->value;
    }

    public function __toString() {
        return $this->value;
    }

    public function format() {
        $offset = 0;
        $args = func_get_args();
        $format = array_shift($args);

        preg_match_all('/(?=\{)\{(\d+)\}(?!\})/', $format, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[1] as $data) {
            $i = $data[0];
            $format = substr_replace($format, @$this->value, $offset + $data[1] - 1, 2 + strlen($i));
            $offset += strlen(@$this->value) - 2 - strlen($i);
        }

        return $format;
    }

}