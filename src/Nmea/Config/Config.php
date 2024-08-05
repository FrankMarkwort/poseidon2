<?php

namespace Nmea\Config;

class Config
{
    public static function getMemcacheHost():string
    {
        return static::getConfigArray()['memcached']['host'];
    }
    public static function getMemcachePort():string
    {
        return static::getConfigArray()['memcached']['port'];
    }
    public static function getMariadbHost():string
    {
        return static::getConfigArray()['mariadb']['host'];
    }
    public static function getMariadbPort():int
    {
        return static::getConfigArray()['mariadb']['port'];
    }
    public static function getMariadbName():string
    {
        return static::getConfigArray()['mariadb']['dbname'];
    }
    public static function getMariadbUser():string
    {
        return static::getConfigArray()['mariadb']['user'];
    }
    public static function getMariadbPassword():string
    {
        return static::getConfigArray()['mariadb']['password'];
    }
    public static function getSerialDevice():string
    {
        return static::getConfigArray()['serialdevice'];
    }
    public static function getSocketServerHost():string
    {
        return static::getConfigArray()['socketServer']['host'];
    }
    public static function getSocketServerPort():string
    {
        return static::getConfigArray()['socketServer']['port'];
    }


    private static function getConfigArray():array
    {
        $array = include(__DIR__ . '/../../config/config.php');

        return $array[static::getRunMode()];
    }

    private static function getRunMode():string
    {
        switch (getenv('RUN_MODE')) {
            case 'develop': return 'develop';
            case 'production': return 'production';
            case 'testing': return 'testing';
            case 'staging': return 'staging';
            default: return 'production';
        }
    }
}