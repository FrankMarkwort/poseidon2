<?php
declare(strict_types=1);

namespace Nmea\Parser\Decode;

use Nmea\AbstractHeader;

class DecodeCanId extends AbstractHeader
{
    public function getBin():string
    {
        return base_convert($this->canIdHex, 16, 2);
    }
}