<?php

namespace Helper\Api\Business;

use SmartFull\Data\Auth;
use SmartFull\Utils\Strings;
use SmartFull\Data\Business;
use SmartFull\Data\ResultSet;
use SmartFull\Data\PageRequest;
use SmartFull\Data\Traits\Blowfish;
use SmartFull\Data\Interfaces\ModelInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @Entity {
 *      "table":"users",
 *      "model":"\\Helper\\Api\\Models\\Users"
 * }
 */
class UsersBusiness extends Business
{
    use Blowfish;

    public function auth(Strings $username, Strings $password, Request $request) : ResultSet {
        $result = $this->getResult();

        $sql = "
            declare
                @username varchar(50) = :username;
                
            SELECT
                u.id,
                u.username,
                u.password,                
                u.isactive,
                u.mainmail
            FROM
                {$this->_table} u
            WHERE u.username = @username";

        try {

            $pdo = $this->getProxy()->prepare($sql);
            $pdo->bindValue(":username", $username, \PDO::PARAM_STR);

            $callback = $pdo->execute();

            if(!$callback) {
                $result->setStatus(401);
                throw new \Exception($result::FAILURE_STATEMENT);
            }

            $rows = (object)$pdo->fetchAll()[0];

            if(count($rows) == 0) {
                $result->setStatus(401);
                throw new \Exception("Credencial inválida!");
            }

            $passwordUser = $rows->password;

            $success = self::tryHash($password->value,$passwordUser);

            $credential = array(
                "iss"=>$request->getUri()->getBaseUrl(),
                "aud"=>$request->getUri()->getScheme(),
                "uid"=>$rows->id,
                "usr"=>$rows->username,
                "pwd"=>$rows->password
            );

            unset($rows->password);

            $token = Auth::createToken($credential);

            $result->setMessage($token);
            $result->setSuccess($success);

        } catch ( \PDOException $e ) {
            $result->setSuccess(false);
            $result->setText($e->getMessage());
        }

        return $result;

    }

//    public function createToken(Strings $credential, Request $request) : ResultSet {
//            /Bearer:.*{.+\}.*/
//        $result = $this->getResult();
//        $pattern = '/Bearer:/';
//        $replacement = "";
//        $subject = $Credential;
//
//        $baerer = preg_replace($pattern, $replacement, $subject);
//
//        $credential = self::objectToJson($Authorization);
//
//        $claims = Auth::getToken($credential);
//
//        $result->setText($claims);
//
//
//        return $result;
//    }

}

//<?php
//$pattern = '/Bearer:.*{.+\\}.*/';
//$subject = 'Bearer: {"iss":"http://localhost/smarthelper","usr":"samuel.dasilva"}';
//$result = preg_match( $pattern, $subject , $matches );
//echo $result;
//print_r($matches);