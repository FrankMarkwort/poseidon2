<?php

namespace Nmea\Protocol;

use Exception;
use Nmea\Cache\CacheInterface;
use Nmea\Protocol\Frames\Frame\Data\Data;
use Nmea\Protocol\Frames\Frames;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Frames\Frame\Header\Header;
use Nmea\Protocol\Socket\Client;

class FramesFactory
{
    private static ?Frames $instance = null;
    private static CacheInterface $cache;
    private static ?Client $socket = null;

    public static function setCache(CacheInterface $cache):void
    {
        static::$cache = $cache;
    }

    public static function setSocket(Client $socket):void
    {
        static::$socket = $socket;
    }

    public static function reset():void
    {
        static::$instance = null;
    }

    private static function getInstance():Frames
    {
        if (!isset(static::$instance)) {

            static::$instance = new Frames(static::$cache, static::$socket);
        }

        return static::$instance;

    }

    /**
     * @throws Exception
     */
    public static function addData(string $nmea2000): void
    {
        list($timestamp, $direction, $canHexId, $data) = explode(' ', self::removeSpecialCharacter($nmea2000)  , 4);

        self::getInstance()->addFrame(new Frame(new Header($canHexId),new Data($data, $direction, $timestamp)));
    }

    private static function removeSpecialCharacter(string $nmea2000):string
    {
        return str_replace(["\r", "\n"], '', $nmea2000);
    }
}