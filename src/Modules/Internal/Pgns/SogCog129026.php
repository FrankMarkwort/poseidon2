<?php

namespace Modules\Internal\Pgns;

use Modules\Internal\Enums\EnumPgns;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class SogCog129026 extends AbstractPgn
{
    protected function getNmeaData(): string
    {
        return $this->cache->get(EnumPgns::COG_SOG->value);
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getSog():float
    {
        return  $this->getFacade()->getFieldValue(5)->getValue();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getCog():float
    {
        return  $this->getFacade()->getFieldValue(4)->getValue();
    }
}