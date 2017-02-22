<?php

namespace Helper\Api\Controllers;

use SmartFull\Data\ResultSet;
use SmartFull\Utils\Strings;
use Helper\Api\Models\Action;
use SmartFull\Data\Controller;
use SmartFull\Data\PageRequest;
use Helper\Api\Business\ActionBusiness;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @RoutePrefix {
 *      "name":"actions"
 * }
 */
class ActionController extends Controller
{
    protected $actionBusiness = null;

    public function __construct(?Request &$request, ?Response &$response)
    {
        parent::__construct($request, $response);
        $this->actionBusiness = new ActionBusiness();
    }

    /**
     * @HttpGet {
     *      "route":"{id}"
     * }
     *
     * @param int $id
     * @return ResultSet
     */
    public function selectModel(int $id) : ResultSet {
        $result = $this->actionBusiness->get($id);
        return $result;
    }

    /**
     * @HttpGet {
     *      "route":"list"
     * }
     *
     * @param int $start
     * @param int $limit
     * @return ResultSet
     */
    public function getAll(int $start = 0, int $limit = 10) : ResultSet {

        $pager  = new PageRequest(array("start"=>$start, "limit"=>$limit));

        $result = $this->actionBusiness->getAll($pager);

        return $result;
    }

    /**
     * @HttpGet {
     *      "route":""
     * }
     *
     * @param string $filter
     * @param int $start
     * @param int $limit
     * @return ResultSet
     */
    public function search(string $filter = "", int $start = 0, int $limit = 10) : ResultSet {

        $string = new Strings($filter);
        $pager  = new PageRequest(array("start"=>$start, "limit"=>$limit));

        $result = $this->actionBusiness->search($string, $pager);

        return $result;
    }

    /**
     * @HttpPost {
     *      "route":""
     * }
     *
     * @param Action $model
     * @return ResultSet
     */
    public function insertModel(Action $model) : ResultSet {
        $result = $this->actionBusiness->insert($model);
        return $result;
    }

    /**
     * @HttpPut {
     *      "route":"{id}"
     * }
     *
     * @param Action $model
     * @return ResultSet
     */
    public function updateModel(Action $model) : ResultSet {
        $result = $this->actionBusiness->update($model);
        return $result;
    }

    /**
     * @HttpDelete {
     *      "route":"{id}"
     * }
     *
     * @param Action $model
     * @return ResultSet
     */
    public function deleteModel(Action $model) : ResultSet {
        $result = $this->actionBusiness->delete($model);
        return $result;
    }

}