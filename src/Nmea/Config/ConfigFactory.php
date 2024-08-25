<?php
/**
 * @author Frank Markwort
 * @date 13.12.2018
 * @email frank.markwort@gmail.com
 * @project Poseidon
 *
 */
namespace Nmea\Config;

class ConfigFactory
{
    private static ConfigPgn|null $configInstance = null;

    public static function create(int $png): PngFieldConfig
    {
        return static::getNewPngFieldConfigInstance()
            ->setConfigInstance(static::getConfigInstance())
            ->setPgn($png);
    }

    private function __construct() {}

    private function __clone() {}

    private static function getNewPngFieldConfigInstance():PngFieldConfig
    {
         return new PngFieldConfig();
    }

    private static function getConfigInstance():ConfigPgn
    {
        if(is_null(static::$configInstance)) {
            static::$configInstance = new ConfigPgn();
        }
        return static::$configInstance;
    }
}

