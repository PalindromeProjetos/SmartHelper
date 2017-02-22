<?php

namespace Helper\Api\Models;

use SmartFull\Data\Model;

/**
 * @Entity {
 *      "table":"action",
 *      "logbook":true
 * }
 */
class Action extends Model
{

    /**
     * @Policy {"nullable":false}
     * @Column {"description":"", "strategy":"AUTO", "type":"integer", "policy":false, "logallow":true, "default":null}
     */
    public $id;

    /**
     * @Policy {"nullable":false, "length":60}
     * @Column {"description":"", "type":"string", "policy":true, "logallow":true, "default":null}
     */
    public $directive;

    /**
     * @Policy {"nullable":false, "length":80}
     * @Column {"description":"", "type":"string", "policy":true, "logallow":true, "default":null}
     */
    public $description;

    /**
     * @Policy {"nullable":false}
     * @Column {"description":"", "type":"boolean", "policy":true, "logallow":true, "default":true}
     */
    public $isactive;

    /**
     * @Policy {"nullable":true, "length":80}
     * @Column {"description":"", "type":"string", "policy":true, "logallow":true, "default":null}
     */
    public $negation;

}