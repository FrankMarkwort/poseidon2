<?php

namespace Modules\Internal\Pgns;

use Modules\Internal\Enums\EnumPgns;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

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