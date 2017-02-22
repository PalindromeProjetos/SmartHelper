<?php

namespace Helper\Api\Models;

use SmartFull\Data\Model;

/**
 * @Entity {
 *      "table":"usersmenu",
 *      "logbook":true
 * }
 */
class UsersMenu extends Model
{

    /**
     * @Policy {"nullable":false}
     * @Column {"description":"", "strategy":"AUTO", "type":"integer", "policy":false, "logallow":true, "default":""}
     */
    public $id;

    /**
     * @Policy {"nullable":false}
     * @Column {"description":"", "type":"integer", "policy":true, "logallow":true, "default":""}
     */
    public $usersid;

    /**
     * @Policy {"nullable":false}
     * @Column {"description":"", "type":"integer", "policy":true, "logallow":true, "default":""}
     */
    public $menuid;

}