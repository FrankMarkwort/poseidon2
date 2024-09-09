<?php

namespace Modules\Internal\Pgns;

use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class Temperature130312 extends AbstractPgn
{
    protected function getNmeaData(): string
    {
         return $this->cache->get(EnumPgns::TEMPERATURE->value);
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getWaterTemperature(): float
    {
        return  $this->getFacade()->getFieldValue(4)->getValue();
    }
}