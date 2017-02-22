<?php

namespace SmartFull\Data;

use SmartFull\Data\ResultSet;
use SmartFull\Data\Traits\Start;

final class Proxy
{
    use Start;

    private static $pdo = null;
    private static $own = null;
    private static $driverName = null;

    private function __construct() {

        self::setTimeZone();
        $pwd = self::getPassWord();
        $usr = self::getUserName();
        $dns = self::getConnnect();

        self::$pdo = new \PDO($dns, $usr, $pwd);

        try {

            self::$driverName = self::$pdo->getAttribute( \PDO::ATTR_DRIVER_NAME );
            self::$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
            self::$pdo->setAttribute( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC );

            if(self::$driverName == 'sqlsrv') {
                if (1 == 1)
                    self::$pdo->setAttribute( \PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_UTF8);
                else
                    self::$pdo->setAttribute( \PDO::SQLSRV_ATTR_ENCODING, \PDO::SQLSRV_ENCODING_SYSTEM);
            }

        } catch ( \PDOException $e ) {
            self::_setSuccess(false);
            self::_setText('Não foi possível acessar a base de dados!');
        }
    }

    public static function borrow() {

        if(!(self::$own instanceof Proxy)) {
            self::$own = new Proxy();
        }

        return self::$pdo;
    }

}