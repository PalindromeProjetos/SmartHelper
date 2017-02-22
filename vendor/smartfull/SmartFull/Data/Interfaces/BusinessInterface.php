<?php

namespace SmartFull\Data\Interfaces;

use SmartFull\Utils\Strings;
use SmartFull\Data\ResultSet;
use SmartFull\Data\PageRequest;
use SmartFull\Data\Interfaces\ModelInterface;

interface BusinessInterface
{

    public function get(int $id) : ResultSet;

    public function getAll(PageRequest $pager) : ResultSet;
    
	public function insert(ModelInterface &$model) : ResultSet;

    public function update(ModelInterface &$model) : ResultSet;

    public function delete(ModelInterface &$model) : ResultSet;

}