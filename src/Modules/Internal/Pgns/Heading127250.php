<?php

namespace Modules\Internal\Pgns;

use Nmea\Config\ConfigException;
use Nmea\Cron\EnumPgns;
use Nmea\Parser\ParserException;

class Heading127250 extends AbstractPgn
{
    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getHeadingRad():float
    {
        return $this->getFacade()->getFieldValue(2)->getValue();
    }

    protected function getNmeaData(): string
    {
        return $this->cache->get(EnumPgns::VESSEL_HEADING->value);
    }
}