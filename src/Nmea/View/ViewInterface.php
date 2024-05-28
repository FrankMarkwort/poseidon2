<?php

namespace Nmea\View;

use Nmea\Parser\Data\DataFacadenColection;

interface ViewInterface
{
    public function __construct(DataFacadenColection $dataFacade);

    public function present(): string;
}