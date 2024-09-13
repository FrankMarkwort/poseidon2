<?php
declare(strict_types=1);

namespace Nmea\Protocol;

use ErrorException;
use Modules\Internal\RealtimeDistributor;
use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;
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
    private static ?RealtimeDistributor $distributor = null;

    public static function setCache(CacheInterface $cache):void
    {
        static::$cache = $cache;
    }

    public static function setRealtimeDistributor(RealtimeDistributor $distributor):void
    {
        static::$distributor = $distributor;
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

            static::$instance = new Frames(static::$cache, static::$socket, static::$distributor);
        }

        return static::$instance;

    }

    /**
     * @throws ErrorException
     * @throws ConfigException
     * @throws ParserException
     */
    public static function addData(string $nmea2000, RealtimeDistributor $realtimeDistributor): void
    {
        list($timestamp, $direction, $canHexId, $data) = explode(' ', self::removeSpecialCharacter($nmea2000)  , 4);

        self::getInstance()->addFrame(new Frame(new Header($canHexId),new Data($data, $direction, $timestamp)));
    }

    private static function removeSpecialCharacter(string $nmea2000):string
    {
        return str_replace(["\r", "\n"], '', $nmea2000);
    }
}