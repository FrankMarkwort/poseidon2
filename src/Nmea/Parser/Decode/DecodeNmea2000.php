<?php

namespace Nmea\Parser\Decode;

use Exception;
use Nmea\Parser\ParserException;
use Nmea\Parser\Lib\BinDec;

class DecodeNmea2000 implements DecoderInterface
{
    private const string ASCCII = 'ASCII text';
    private static self $instance;
    private string $binString;

    public static function getInstance() : DecoderInterface
    {
        self::$instance = new self();

        return self::$instance;
    }

    private function __construct() {}

    private function __clone() {}

    /**
     * @throws ParserException
     */
    public function setArray(array $dataArray, int $length): DecoderInterface
    {
        if (count($dataArray) !== $length) {

            throw new ParserException('length is false ' . count($dataArray) . ' != '.$length);
        }
        $this->binString = $this->convertHexArrayToBinString($dataArray);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getValue(Request $request) : float|int|string|null
    {
        $bin = strrev(substr($this->binString, $request->getBitOffset(), $request->getBitLength()));
        if ($request->getType() === static::ASCCII) {
            $result =  BinDec::binToAscii($bin);
        } elseif (in_array($request->getType(),['INTEGER', 'Manufacturer code', 'Lookup table'])) {
            $result = intval(BinDec::bin2dec($bin, $request->getSignet(), $request->getResolution()));
            $result = $this->ifValueOutOfRangeThenReturnNull($request, $result);
        } elseif($request->getType() === 'Binary data') {
            $result = $bin;
        } else {
            $result = floatval(BinDec::bin2dec($bin, $request->getSignet(), $request->getResolution()));
            $result = $this->ifValueOutOfRangeThenReturnNull( $request, $result);
        }
        return $result;
    }

    private function ifValueOutOfRangeThenReturnNull(Request $request,  int|float $value) :int|float|null
    {
        if (! (is_null($request->getRangeMax()) || is_null($request->getRangeMin()))) {
            if ($value > $request->getRangeMax()) {
                $value = null;
            } elseif ($value < $request->getRangeMin()) {
                $value = null;
            }
        }
        return $value;
    }

    private function convertHexArrayToBinString(array $dataArray):string
    {
        $binString = '';
        foreach ($dataArray as $value) {
            $binString .= strrev(sprintf('%08b',intval($value,16)));
        }

        return $binString;
    }
}
