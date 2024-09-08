<?php

namespace Modules\Internal\Pgns;

use Nmea\Config\ConfigException;
use Nmea\Cron\EnumPgns;
use Nmea\Parser\ParserException;

class Gps129025 extends AbstractPgn
{
    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getLatitudeDeg():float
    {
        return $this->getFacade()->getFieldValue(1)->getValue();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getLongitudeDeg():float
    {
        return $this->getFacade()->getFieldValue(2)->getValue();
    }

    protected function getNmeaData():string
    {
        return $this->getCache()->get(EnumPgns::POSITION->value);
    }
}