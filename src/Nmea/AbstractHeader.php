<?php

namespace Nmea;

use Nmea\Parser\Lib\BinDec;
use Nmea\Protocol\Frames\Frame\Header\PackedTypeHelper;

abstract class AbstractHeader
{
    public function __construct(protected readonly string $canIdHex)
    {
        if (!$this->isSingelPacked() && !$this->isFastPacked()) {

            throw new \Exception($this->getPgn() . ' is not a singlePacked and not a fastPacked. Look src/Nmea/Protocol/Frames/Frame/Header/PackedTypeHelper.php');
        }
    }

    public function getCanIdDec()
    {
        return hexdec($this->canIdHex);
    }
    public function getPgn():int
    {
        return (($this->getReserved() << 17) | ($this->getDataPage() << 16) | ($this->getPduFormat() << 8))
            | ($this->getPduFormat() < 240 ? 0 : ($this->getPduSpecific() << 0));
    }
    public function getCanIdHex():string
    {
        return $this->canIdHex;
    }
    public function getCanExtendedId():int
    {
        return $this->decode($this->getCanIdDec(), 29, 000);
    }

    private function decode(int $value, int $bitPosition, string $bitMask):int
    {
        return ($value >> $bitPosition) & bindec($bitMask);
    }
    public function getPriority():int
    {
        return $this->decode($this->getCanIdDec(), 26, 111);
    }

    public function getReserved()
    {
        return $this->decode($this->getCanIdDec(), 25, 1);
    }

    public function getDataPage():int
    {
        return $this->decode($this->getCanIdDec(), 24, 1);
    }

    public function getExtendedDataPage():int
    {
        return $this->getReserved();
    }

    public function getPduFormat()
    {
        return $this->decode($this->getCanIdDec(), 16, 11111111);
    }

    public function getPduSpecific():int
    {
        return $this->decode($this->getCanIdDec(), 8, 11111111);
    }

    public function getSourceAdress()
    {
        return $this->decode($this->getCanIdDec(), 0, 11111111);
    }

    public function getDestination()
    {
        if ($this->getPduFormat() < 240) {

            return $this->getPduSpecific();
        }

        return 255;
    }

    public function getGroupExtension()
    {
        if ($this->getPduFormat() >= 240) {

            return $this->getPduSpecific();
        }

        return 0;
    }

    public function isSingelPacked():bool
    {
        return PackedTypeHelper::isSinglePacked($this->getPgn());
    }

    public function isFastPacked():bool
    {
        return PackedTypeHelper::isFastPacked($this->getPgn());
    }

    public function isNmea2000Packed():bool
    {
        return $this->getDataPage() === 1 && $this->getExtendedDataPage() === 0;
    }

    public function isPdu1Format():bool
    {
        return $this->getPduFormat() < 240;
    }

    public function isPdu2Format():bool
    {
        return $this->getPduFormat() >= 240;
    }
}