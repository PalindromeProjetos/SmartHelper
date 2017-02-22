<?php
/**
 * Created by PhpStorm.
 * User: SamuelOliveira
 * Date: 05/02/2017
 * Time: 13:18
 */

namespace SmartFull\Data\Interfaces;

use Psr\Http\Message\ServerRequestInterface;
use SmartFull\Data\Interfaces\ControllerInterface;

interface MappingRouterInterface
{

    /**
     * @return array
     */
    public function getRoutes();

    public function hasRoutesConflict();

    /**
     * @param $method
     * @param $path
     * @return mixed
     */
    public function mapRouter(ServerRequestInterface $request);

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function hydratedRoute(ServerRequestInterface $request);

}