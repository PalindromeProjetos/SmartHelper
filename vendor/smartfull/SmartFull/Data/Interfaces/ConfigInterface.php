<?php

namespace SmartFull\Data\Interfaces;

interface ConfigInterface
{

    public function getConfig();

	public function getControllers($path);

    public function setConfig(\ArrayObject $config);

}