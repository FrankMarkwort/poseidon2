<?php

namespace Modules\Internal\Pgns;

use Modules\Internal\Enums\EnumPgns;
use Core\Config\ConfigException;
use Core\Parser\ParserException;

class SetAndDrift129291 extends AbstractPgn
{
    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getDrift():float
    {
        return $this->getFacade()->getFieldValue(5)->getValue();
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    public function getSet():float
    {
        return $this->getFacade()->getFieldValue(4)->getValue();
    }

    protected function getNmeaData(): string
    {
        return $this->getCache()->get(EnumPgns::SET_AND_DRIFT->value);
    }
}