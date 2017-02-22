<?php

namespace SmartFull\Data;

final class Session
{

    const _SESSION_DATE = 60*60*23*1;
    const _SESSION_NAME = 'smartfull';
    const _SESSION_PATH = '/../session/smartfull/';

    private static $hdl = null;
    private static $own = null;
    private static $name = self::_SESSION_NAME;
    private static $date = self::_SESSION_DATE;
    private static $path = self::_SESSION_PATH;

    private function __construct() {

    }

    public static function borrow() {

        if(!(self::$own instanceof Session)) {
            self::$own = new Session();
        }

        return self::$hdl;
    }

}