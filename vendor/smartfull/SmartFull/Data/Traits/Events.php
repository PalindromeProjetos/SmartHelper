<?php

namespace SmartFull\Data\Traits;

use SmartFull\Data\Interfaces\ModelInterface;

trait Events
{

    /**
     * Evento antes do SQLUpdate
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function preUpdate( ModelInterface &$model ) {
    }

    /**
     * Evento após o SQLUpdate
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function posUpdate( ModelInterface &$model ) {
    }

    /**
     * Evento antes do SQLInsert
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function preInsert( ModelInterface &$model ) {
    }

    /**
     * Evento após o SQLInsert
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function posInsert( ModelInterface &$model ) {
    }

    /**
     * Evento antes do SQLDelete
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function preDelete( ModelInterface &$model ) {
    }

    /**
     * Evento após o SQLDelete
     *
     * @param type <b>&$entity</b> by reference
     *
     * Call Exception if necessary<br/>
     * Example:<br/>
     * throw new \PDOException('this error');<br/>
     *
     * @category listeners
     */
    protected function posDelete( ModelInterface &$model ) {
    }

}