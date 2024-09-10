<?php

namespace Modules\Internal\Pgns;

use Modules\Internal\Enums\EnumPgns;
use Nmea\Config\ConfigException;
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
        return $this->getCache()->get(EnumPgns::VESSEL_HEADING->value);
    }
}