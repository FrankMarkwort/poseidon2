<?php
declare(strict_types=1);

namespace Core\Parser\Lib;

use Exception;

class BinDec
{
    /**
     * @throws Exception
     */
    public static function bin2dec64BitSystem(string $bin, bool $signed = false ,float|int|null $resolution = 1):float|int
    {
        if (! static::isBinary($bin)) {

            throw new Exception("is not BinaryString $bin");
        }

        if (! $signed) {
            $result = bindec($bin);
            if (is_numeric($resolution)) {
                $result = $result * $resolution;
            }

            return $result;
        }

        if (! in_array(strlen($bin), [4 ,8, 16 ,32 ,64])) {

            throw new Exception('can only convert 4 ,8, 16 ,32 ,64 length to dec');
        }

        if ($bin[0] == '1') {
            $bin = static::bitFlip($bin);
            $result =  (bindec($bin) + 1) * -1;
            if (is_numeric($resolution)) {
                $result = $result * $resolution;
            }
            return $result;
        }
        $result = bindec($bin);
        if (is_numeric($resolution)) {
            $result = $result * $resolution;
        }
        return $result;
    }

    /**
     * @throws Exception
     */

    public static function bin2dec(string $bin, bool $signed = false, float|int|null $resolution = 1):float|int
    {
        if ( static::is_32bit() ) {

            return self::bin2dec32BitSystem($bin, $signed, $resolution);
        }

        return self::bin2dec64BitSystem($bin, $signed, $resolution);
    }

    /**
     * @throws Exception
     */
    public static function bin2dec32BitSystem(string $bin, bool $signed = false, float|int|null $resolution = 1):float
    {
        if (! static::isBinary($bin)) {

           throw new Exception("is not BinaryString $bin");
        }
        $result = '0';
        $firstBit = $bin[0];
        if ($signed && $firstBit == '1') {
            $bin = static::bitFlip($bin);
        }
        for($i=0; $i<strlen($bin); $i++) {
            $result = BCAdd(BCMul($result,'2',0), $bin[$i], 0);
        }
        if ($signed && $firstBit == '1') {
            $result = BCMul(BCAdd($result, '1', 0), '-1', 0);
        }
        if (is_numeric($resolution) ) {
            $result =  BCMul($result,  number_format($resolution, 64, '.', null), 64);
        }

        return floatval($result);
    }

    /**
     * @throws Exception
     */
    public static function binToAscii(string $bin):string
    {
        static::validBitLength($bin);
        if (! static::isBinary($bin)) {

           throw new Exception('is not BinaryString');
        }
        $result = '';
        for($i=0; $i<strlen($bin); $i+=8) {
            $result .= chr(intval(substr($bin, $i, 8), 2));
        }

        return $result;
    }

    private static function isBinary(string $string): bool
    {
        return preg_match("/^[01]+$/", $string) >= 1;
    }

    /**
     * @throws Exception
     */
    private static function validBitLength(string $bin):void
    {
        if (! in_array(strlen($bin), [4 ,8, 16 ,32 ,64])) {
            throw new Exception('can only convert 4 ,8, 16 ,32 ,64 length to dec');
        }
    }

    private static function bitFlip(string $bin):string
    {
        for($i = 0; $i < strlen($bin); $i++) {
            $bin[$i] = $bin[$i] == '1' ? '0' : '1';
        }

        return $bin;
    }

    private static function is_32bit():bool
    {
        return PHP_INT_SIZE === 4;
    }
}
