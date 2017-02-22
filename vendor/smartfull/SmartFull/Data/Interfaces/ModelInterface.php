<?php

namespace SmartFull\Data\Interfaces;

interface ModelInterface extends \SmartFull\Interfaces\ObjectBaseInterface
{

	public function __toString() : string;

	public function toArray () : array;

    public function hydrateModel(\stdClass $entity = null);

}