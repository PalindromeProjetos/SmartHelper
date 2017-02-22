<?php

namespace Helper\Api\Business;

use SmartFull\Utils\Strings;
use SmartFull\Data\Business;
use SmartFull\Data\ResultSet;
use SmartFull\Data\PageRequest;
use SmartFull\Data\Interfaces\ModelInterface;

/**
 * @Entity {
 *      "table":"action",
 *      "model":"\\Helper\\Api\\Models\\Action"
 * }
 */
class ActionBusiness extends Business
{

    public function search(Strings $filter, PageRequest $pager): ResultSet {
        $start = $pager->getStart();
        $limit = $pager->getLimit();
        $result = $this->getResult();

        $sql = "
            declare
                @start int = :start,
                @limit int = :limit,
                @filter varchar(50) = :filter;

            WITH pager AS (
                SELECT
                    *,
                    ROW_NUMBER() OVER (ORDER BY id) AS rowindex
                FROM
                    {$this->_table}
                where description like @filter
            )
            SELECT
                *,
				records = ( select count(id) from pager )
            FROM
                pager
            WHERE rowindex BETWEEN @start AND @limit";

        try {

            $pdo = $this->getProxy()->prepare($sql);
            $pdo->bindValue(":start", $start, \PDO::PARAM_INT);
            $pdo->bindValue(":limit", $limit, \PDO::PARAM_INT);
            $pdo->bindValue(":filter", $filter->format('%{0}%'), \PDO::PARAM_STR);

            $callback = $pdo->execute();

            if(!$callback) {
                throw new \PDOException($result::FAILURE_STATEMENT);
            }

            $rows = $pdo->fetchAll();

            $records = (count($rows) != 0) ? $rows[0]['records'] : 0;

            $result->setRows($rows);
            $result->setRecords($records);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;

    }

}