<?php

namespace SmartFull\Data\Traits;

trait Start
{

    private static $usr = "sa";
    private static $sch = "dbo";
    private static $dtb = "CME";
    private static $pwd = "1844";
    private static $log = "CME_LOG";
    private static $tmz = "America/Sao_Paulo";
    private static $dns = "sqlsrv:server=(local);database=dtb";

    public static function tableSchema() {
        return self::$sch;
    }
    public static function getPassWord() {
        return self::$pwd;
    }
    public static function getUserName() {
        return self::$usr;
    }
    public static function getTimeZone() {
        return self::$tmz;
    }
    public static function getDataBase() {
        return self::$dtb;
    }
    public static function areTestBase() {
        return is_numeric(strripos(self::getDataBase(), 'test'));
    }
    public static function setTimeZone() {
        date_default_timezone_set(self::$tmz);
    }
    public static function getConnnect() {
        return str_replace("dtb",self::$dtb,self::$dns);
    }
    public static function logConnnect() {
        return str_replace("dtb",self::$log,self::$dns);
    }

}