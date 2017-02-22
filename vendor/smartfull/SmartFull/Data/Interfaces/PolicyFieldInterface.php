<?php

namespace SmartFull\Data\Interfaces;

interface PolicyFieldInterface
{

    // TODO: Validar Tipo de dado
    // TODO: Validar Nullable
    // TODO: Validar DefaultValue
    // TODO: Validar Tamanho Máximo
    
}

/*
 * http://php.net/manual/en/function.filter-var.php
 * http://php.net/manual/en/function.filter-var-array.php
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