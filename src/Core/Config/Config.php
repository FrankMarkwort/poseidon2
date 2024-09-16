<?php
declare(strict_types=1);

namespace Core\Config;

class Config
{
    private const string HOST = 'host';
    private const string PORT = 'port';
    private const string MEMCACHED = 'memcached';
    private const string MARIADB = 'mariadb';
    private const string USER = 'user';
    private const string PASSWORD = 'password';
    private const string DBNAME = 'dbname';
    public static function getMemcacheHost():string
    {
        return static::getConfigArray()[static::MEMCACHED][static::HOST];
    }
    public static function getMemcachePort():int
    {
        return static::getConfigArray()[static::MEMCACHED][static::PORT];
    }
    public static function getMariadbHost():string
    {
        return static::getConfigArray()[static::MARIADB][static::HOST];
    }
    public static function getMariadbPort():int
    {
        return static::getConfigArray()[static::MARIADB][static::PORT];
    }
    public static function getMariadbName():string
    {
        return static::getConfigArray()[static::MARIADB][static::DBNAME];
    }
    public static function getMariadbUser():string
    {
        return static::getConfigArray()[static::MARIADB][static::USER];
    }
    public static function getMariadbPassword():string
    {
        return static::getConfigArray()[static::MARIADB][static::PASSWORD];
    }
    public static function getSerialDevice():string
    {
        return static::getConfigArray()['serialdevice'];
    }
    public static function getSocketServerHost():string
    {
        return static::getConfigArray()['socketServer'][static::HOST];
    }
    public static function getSocketServerPort():int
    {
        return static::getConfigArray()['socketServer'][static::PORT];
    }

    public static function getApiServerHost():string
    {
        return static::getConfigArray()['apiServer'][static::HOST];
    }

    public static function getApiServerPort():int
    {
        return static::getConfigArray()['apiServer'][static::PORT];
    }

    private static function getConfigArray():array
    {
        $array = include(__DIR__ . '/../../config/config.php');

        return $array[static::getRunMode()];
    }

    private static function getRunMode():string
    {
        return match (getenv('RUN_MODE')) {
            'develop' => 'develop',
            'testing' => 'testing',
            'staging' => 'staging',
            default => 'production',
        };
    }
}
