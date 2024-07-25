<?php

namespace Nmea\Protocol\Frames\Frame\Header;

class PackedTypeHelper
{
    private static array $singlePackedPngsInMixed = [130306, 128259, 130310, 130312, 128267, 127250, 127245, 129026, 129025, 127258,129033,126992,
        129291,129283,126993, 130311
    ];

    private static array $fastPackedPgnsInMixed = [128275, 129542, 129540, 129029, 130577, 129044, 126996];

    public static function isSinglePacked(int $pgn):bool
    {
         return ( static::isBetween($pgn, 0xE800, 0xEE00)
             || ($pgn == 0xEF00)
             || static::isBetween($pgn,0xF000,0xFEFF)
             || static::isBetween($pgn,0xFF00,0xFFFF)
             || static::isBetween($pgn,0x1ED00,0x1EE00)
             || static::inMixedRangeAndIsSinglePacked($pgn)
         );
    }

    public static function isFastPacked(int $pgn):bool
    {
         return (($pgn === 126720) || static::isBetween($pgn,130816,131071) || static::inMixedRangeAndIsFastPacked($pgn));
    }
    private static function inMixedRangeAndIsSinglePacked(int $pgn):bool
    {
        return static::isMixedSingleFastPacked($pgn) && in_array($pgn, self::$singlePackedPngsInMixed);
    }
    private static function inMixedRangeAndIsFastPacked(int $pgn):bool
    {
        return static::isMixedSingleFastPacked($pgn) && in_array($pgn, self::$fastPackedPgnsInMixed);
    }
    private static function isMixedSingleFastPacked(int $pgn):bool
    {
        return (static::isBetween($pgn, 126976,130815));
    }
    private static function isBetween(int $png, int $min, int $max):bool
    {
        return ($png >= $min &&  $png <= $max);
    }
}