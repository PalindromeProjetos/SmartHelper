<?php

namespace SmartFull\Data;

use SmartFull\Data\Model;
use SmartFull\Data\Store;
use SmartFull\Utils\Strings;
use SmartFull\Data\ResultSet;
use SmartFull\Data\PageRequest;
use SmartFull\Data\Traits\Annotations;
use SmartFull\Data\Interfaces\ModelInterface;
use SmartFull\Data\Interfaces\BusinessInterface;

class Business extends Store implements BusinessInterface
{
    use Annotations;

    public $_table = null;

    public function __construct()
    {
        $this->setNotation(array(
            'instance'=>array('Entity')
        ));

		$this->_notate = $this->getAnnotations($this);
        $this->_table  = $this->_notate->instance['Entity']->table;

        parent::__construct();
    }

    public function get(int $id) : ResultSet {
        $result = $this->getResult();

        try {

            $sql = "
				declare
					@id int = :id;

					SELECT * FROM {$this->_table} WHERE id = @id;";

            $pdo = $this->getProxy()->prepare($sql);
            $pdo->bindValue(":id", $id, \PDO::PARAM_INT);

            $callback = $pdo->execute();

            if(!$callback) {
                throw new \PDOException($result::FAILURE_STATEMENT);
            }

            $rows = $pdo->fetchAll();

            $result->setRows($rows);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;
    }

    public function getAll(PageRequest $pager) : ResultSet {
        $start = $pager->getStart();
        $limit = $pager->getLimit();
        $result = $this->getResult();

        $sql = "
            declare
                @start int = :start,
                @limit int = :limit;

            WITH pager AS (
                SELECT
                    *,
                    ROW_NUMBER() OVER (ORDER BY id) AS rowindex
                FROM
                    {$this->_table}
            )
            SELECT
                *,
				total = ( select count(id) from pager )
            FROM
                pager
            WHERE rowindex BETWEEN @start AND @limit";

        try {

            $pdo = $this->getProxy()->prepare($sql);
            $pdo->bindValue(":start", $start, \PDO::PARAM_INT);
            $pdo->bindValue(":limit", $limit, \PDO::PARAM_INT);

            $callback = $pdo->execute();

            if(!$callback) {
                throw new \PDOException($result::FAILURE_STATEMENT);
            }

            $rows = $pdo->fetchAll();

            $records = (count($rows) != 0) ? $rows[0]['total'] : 0;

            $result->setRows($rows);
            $result->setRecords($records);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;
    }

	public function insert(ModelInterface &$model) : ResultSet {
		$result = $this->getResult();

		try {

			$this->preInsert($model);


			$this->posInsert($model);

		} catch ( \PDOException $e ) {
			$result->setSuccess(false);
			$result->setText($e->getMessage());
		}

		return $result;

	}

    public function update(ModelInterface &$model) : ResultSet {
        $result = $this->getResult();

        try {

            $this->preUpdate($model);

            $statement = $this->sqlUpdate($model);

            $callback = $statement->execute();

            if(!$callback) {
                throw new \PDOException($result::FAILURE_STATEMENT);
            }

            $this->posUpdate($model);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;

    }

    public function delete(ModelInterface &$model) : ResultSet {
        $result = $this->getResult();

        try {

            $this->preDelete($model);


            $this->posDelete($model);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;

    }

}