<?php

namespace Modules\Internal\Pgns;

use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\ParserException;

abstract class AbstractPgn
{
    public function __construct(protected CacheInterface $cache)
    {
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    protected function getFacade():DataFacade
    {
        return DataFacadeFactory::create($this->getNmeaData(), 'YACHT_DEVICE');
    }

    abstract protected function getNmeaData():string;
}