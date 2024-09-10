<?php

namespace Modules\Internal\Pgns;

use Modules\Internal\Enums\EnumPgns;
use Nmea\Config\ConfigException;
use Nmea\Parser\ParserException;

class WaterDepth128267 extends AbstractPgn
{
    /**
     * @throws ParserException
     * @throws ConfigException
     */
    public function getWaterDepth():float
    {
        return  $this->getFacade()->getFieldValue(2)->getValue() + $this->getFacade()->getFieldValue(3)->getValue();
    }

    protected function getNmeaData():string
    {
        return $this->cache->get(EnumPgns::WATER_DEPTH->value);
    }
}
