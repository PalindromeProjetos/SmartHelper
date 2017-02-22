<?php

namespace SmartFull\Data\Traits;

trait ArrayTools
{

    public static function getJsonErro ($silent = false) {
        $error = array("message"=>"");

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                $error["message"] = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error["message"] = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error["message"] = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error["message"] = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error["message"] = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            case JSON_ERROR_RECURSION:
                $error["message"] = 'One or more recursive references in the value to be encoded';
                break;
            case JSON_ERROR_INF_OR_NAN;
                $error["message"] = 'One or more NAN or INF values in the value to be encoded';
                break;
            case JSON_ERROR_UNSUPPORTED_TYPE;
                $error["message"] = 'A value of a type that cannot be encoded was given';
                break;
            default:
                $error["message"] = 'Unknown error';
                break;
        }

        $error['error'] = strlen($error["message"]) != 0;

        if($error['error'] && $silent == false) {
            throw new \Exception($error["message"]);
        }
    }

    public static function encodeUTF8(array $array) : array {
        $convertedArray = array();

        foreach($array as $key => $value) {
            if (is_array($value)) {
                $value = self::encodeUTF8($value);
            } else if (!(bool)preg_match('//u', serialize($value))) {
                $value = utf8_encode($value);
            }

            $convertedArray[$key] = $value;
        }

        return $convertedArray;
    }

    public static function decodeUTF8(array $array) : array {
        $convertedArray = array();

        foreach($array as $key => $value) {
            if (is_array($value)) {
                $value = self::decodeUTF8($value);
            } else if ((bool)preg_match('//u', serialize($value))) {
                $value = utf8_decode($value);
            }

            $convertedArray[$key] = $value;
        }

        return $convertedArray;
    }

    /**
     * Pesquisa recursiva em um multidimensional array in key=>value
     *
     * @return array Contém a estrutura de retorno
     * @author http://stackoverflow.com/questions/1019076/how-to-search-by-key-value-in-a-multidimensional-array-in-php
     */
    public static function searchArray ($array, $key, $value) : array {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, self::searchArray($subarray, $key, $value));
            }
        }

        return $results;
    }

    /**
     * Pesquisa recursiva em um multidimensional array multiplas chaves
     *
     * @return array Contém a estrutura de retorno
     */
    public static function recordArray ($array, $field) : array {
        $results = $array;

        foreach($field as $key=>$value) {
            $results = self::searchArray($results, $key, $value);
        }

        return $results;
    }

    /**
     * Ordenar um multidimensional array in key=>value
     *
     * @return array Contém a estrutura de retorno
     * @author http://php.net/manual/pt_BR/function.sort.php
     */
    public static function sorterArray ($array, $key, $order=SORT_ASC) : array {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $key) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[] = $array[$k];
            }
        }

        return $new_array;
    }

    /**
     * Pesquisa recursiva em um multidimensional array in ke=>value
     *
     * @param $array
     * @param $key
     * @return array
     */
    public static function selectArray ($array, $key) : array {
        $results = array();

        for ($x = 0; $x <= count($array)-1; $x++) {
            $results[] = $array[$x][$key];
        }

        return $results;
    }

    /**
     * Elimina elementos duplicados
     *
     * @param $array
     * @return array
     *
     * @author http://stackoverflow.com/questions/3598298/php-remove-duplicate-values-from-multidimensional-array
     */
    public static function uniqueArray ($array) : array {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

        foreach ($result as $key => $value) {
            if ( is_array($value) ) {
                $result[$key] = self::uniqueArray($value);
            }
        }

        return $result;
    }

    /**
     * Analisa um array e converte-a em uma a string codificada JSON. Retorna a representação JSON do array
     *
     * @param array $param Um array associativo/indexado
     * @return string String codificada JSON
     */
    public static function arrayToJson (array $param) : string {
        $result = json_encode(self::encodeUTF8($param));

        self::getJsonErro();

        return $result;
    }

    /**
     * Analisa a string codificada JSON e converte-a em uma array associativo.
     *
     * @param string $param String codificada JSON
     * @return array Um array associativo.
     */
    public static function jsonToArray ($param) : array {
        $result = json_decode($param,true);

        self::getJsonErro();

        return self::decodeUTF8($result);
    }

    /**
     * Retorna um array associativo
     *
     * @param object $param stdClass
     * @return array Um array associativo.
     */
    public static function objectToArray($param) : array {
        $result = json_decode(json_encode($param),true);

        self::getJsonErro();

        return $result;
    }

    /**
     * Retorna uma string json
     *
     * @param object $param stdClass
     * @return string Uma string json.
     */
    public static function objectToJson($param) : string {
        $result = json_encode($param);

        self::getJsonErro();

        return $result;
    }

    /**
     * Retorna um bjeto stdClass
     *
     * @param array $param
     * @return object Um objeto stdClass.
     */
    public static function arrayToObject($param) : \stdClass {
        $result = json_decode(json_encode($param),false);

        self::getJsonErro();

        return $result;
    }

    /**
     * Analisa a string codificada JSON e converte-a em uma objeto stdClass.
     *
     * @param string $param String codificada JSON
     * @return object
     */
    public static function jsonToObject($param) : \stdClass {
        $result = json_decode($param);

        self::getJsonErro();

        return $result;
    }

    /**
     * Analisa a string codificada JSON e converte-a em uma objeto stdClass.
     *
     * @param string $param String codificada JSON
     * @return object
     */
    public static function jsonTryToObject($param, $silent = true) {
        $result = json_decode($param);

        self::getJsonErro($silent);

        return $param;
    }

}