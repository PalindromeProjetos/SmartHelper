<?php

namespace SmartFull\Data;

use Latte\Object;
use SmartFull\Data\Traits\ArrayTools;

class ResultSet extends \ArrayObject
{
    use ArrayTools;

    const EMPTY_RESULT = "Não há registros a serem mostrados!";
    const REQUEST_SUCCESSFUL = "Solicitação completada com sucesso!";
    const FAILURE_STATEMENT = "Houve falha na execução da solicitação!";

    public function __construct (array $seed = null) {
        $result = isset($seed) ? $seed : array(
            "text"=>self::REQUEST_SUCCESSFUL,
            "rows"=>[],
            "errors"=>[],
			"status"=>200,			
            "records"=>00,			
            "success"=>true,
            "message"=>null,
            "restart"=>null
        );

        parent::__construct($result);
    }

    /**
     * @return string
     */
    public function __toString () : string {
		return self::objectToJson($this);
    }

    /**
     * @return Object
     */
	public function toObject () : Object {
        return $this;
    }

    /**
     * @return array
     */
    public function toArray () : array {
        return self::objectToArray($this);
    }

    public function setText($value) {
        $this['text'] = $value;
    }

    public function setRows(array $value, $count = null) {
        $this['rows'] = self::encodeUTF8($value);
        $this->setRecords(!empty($count) ? (int)$count : count($value));
    }

    /**
     * @return bool
     */
    public function hasErrors() : bool {
        return count($this['errors']) != 0;
    }

	public function setErrors(array $value) {
		$this['errors'] = $value;
	}

	public function setStatus($value) {
		$this['status'] = (int)$value;
    }

    /**
     * @return int
     */
    public function getStatus() : int {
        return $this['status'];
    }

    public function setSuccess($value) {
        $this['success'] = (boolean)$value;
    }

    public function setMessage($value) {
        $this['message'] = $value;
    }

    public function setRestart($value) {
        $this['restart'] = (boolean)$value;
    }

    public function setRecords($value) {
        $this['records'] = (int)$value;
        $this->setSuccess( ((int)$value) ? (int)$value : $this['success'] );
        $this->setText( ((int)$value) ? $this['text'] : self::EMPTY_RESULT );
    }

}