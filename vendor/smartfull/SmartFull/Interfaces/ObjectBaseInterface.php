<?php

namespace SmartFull\Interfaces;

interface ObjectBaseInterface
{
    const UNKNOWN_METHOD = "Método inexistente na Classe!";
    const UNKNOWN_PROPERTY = "Propriedade inexistente na Classe!";

    public function __get($field);

    public function __set($field, $value);

}