<?php

namespace SmartFull\Data\Interfaces;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ControllerInterface
{

    public function __construct(?Request &$request, ?Response &$response);

    public function getRoutes() : array;

    public function getRequest(): Request;

    public function getResponse(): Response;

}