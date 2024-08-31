<?php

namespace Nmea\Protocol\Frames\Frame;

use Nmea\Protocol\Frames\Frame\Data\Data;
use Nmea\Protocol\Frames\Frame\Header\Header;

readonly class Frame
{
    public function __construct(private Header $header, private Data $data)
    {
    }
    public function getHeader():Header
    {
        return $this->header;
    }

    public function getData():Data
    {
        return $this->data;
    }

    public function getSequenceCounter():int
    {
        if($this->header->isFastPacked()) {

            return $this->getNHighBits($this->getData()->getFirstByte());

        }

        return 0;
    }
     public function getFrameCounter():int
    {
       if ($this->header->isSingelPacked()) {

            return 0;
       }
       return $this->getLowerBits($this->getData()->getFirstByte());
    }

    private function getLowerBits(string $hex):int
    {
        return  hexdec($hex) & 0x1F;
    }

    private function getNHighBits(string $hex, int $n = 5):int
    {
        return  (hexdec($hex) & 0xE0) >> $n;
    }

    public function numberOfFrames():int
    {
        if ($this->getHeader()->isFastPacked() && $this->getFrameCounter() === 0) {

            return ceil((($this->totalNumberOfBytes() - 6) / 7) + 1);
         }

        return -1;
    }

    private function totalNumberOfBytes():int
    {
        return hexdec($this->getData()->getSecondByte());
    }
}