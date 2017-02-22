<?php

namespace Helper\Api\Controllers;

use SmartFull\Utils\Strings;
use SmartFull\Data\ResultSet;
use SmartFull\Data\Controller;
use SmartFull\Data\PageRequest;
use Helper\Api\Business\UsersBusiness;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @RoutePrefix {
 *      "name":"users"
 * }
 */
class UsersController extends Controller
{

    protected $usersBusiness = null;

    public function __construct(?Request &$request, ?Response &$response)
    {
        parent::__construct($request, $response);
        $this->usersBusiness = new UsersBusiness();
    }

    /**
     * @HttpGet {
     *      "route":"list"
     * }
     */
    public function getAll(int $start = 0, int $limit = 10) : ResultSet {
        $pager  = new PageRequest(array("start"=>$start, "limit"=>$limit));

        $result = $this->usersBusiness->getAll($pager);

        return $result;
    }

    /**
     * @HttpGet {
     *      "route":"{id}"
     * }
     */
    public function getById(int $id) : ResultSet {
        $result = $this->usersBusiness->get($id);
        return $result;
    }

    /**
     * @HttpPost {
     *      "route":"auth"
     * }
     */
    public function authCredential(Strings $username, Strings $password) : ResultSet {
        $result = $this->usersBusiness->auth($username, $password, $this->getRequest());
        return $result;
    }

//    /**
//     * @HttpPost {
//     *      "route":"done"
//     * }
//     */
//    public function createToken(Strings $Authorization) : ResultSet {
//        $result = $this->usersBusiness->createToken($Authorization);
//        return $result;
//    }

}
