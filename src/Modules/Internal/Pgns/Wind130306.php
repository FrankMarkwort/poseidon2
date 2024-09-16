<?php

namespace Modules\Internal\Pgns;


use Modules\Internal\Enums\EnumPgns;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class Wind130306 extends AbstractPgn
{

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getTimestamp():string
    {
        return $this->getFacade()->getTimestamp();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getAwaRad():float
    {
        return $this->getFacade()->getFieldValue(3)->getValue();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getAws():float
    {
         return $this->getFacade()->getFieldValue(2)->getValue();
    }

    protected function getNmeaData(): string
    {
        return $this->cache->get(EnumPgns::WIND->value);
    }
}