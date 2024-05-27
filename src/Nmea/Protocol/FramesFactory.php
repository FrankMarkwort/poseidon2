<?php

namespace Nmea\Protocol;

use Nmea\Cache\CacheInterface;
use Nmea\Protocol\Frames\Frame\Data\Data;
use Nmea\Protocol\Frames\Frames;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Frames\Frame\Header\Header;

class FramesFactory
{
    private static ?Frames $instance;
    private static CacheInterface $cache;

    public static function setCache(CacheInterface $cache)
    {
        static::$cache = $cache;
    }

    public static function reset()
    {
        static::$instance = null;
    }

    private static function getInstance():Frames
    {
        if (!isset(static::$instance)) {

            static::$instance = new Frames(static::$cache);
        }

        return static::$instance;

    }

    public static function addData(string $nmea2000)
    {
        list($timestamp, $direction, $canHexId, $data) = explode(' ', self::removeSpecialCharacter($nmea2000)  , 4);

        self::getInstance()->addFrame(new Frame(new Header($canHexId),new Data($data, $direction, $timestamp)));
    }

    private static function removeSpecialCharacter(string $nmea2000):string
    {
        return str_replace(["\r", "\n"], '', $nmea2000);
    }
}