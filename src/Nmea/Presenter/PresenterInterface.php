<?php

namespace Nmea\Presenter;

use Nmea\Parser\Data\DataFacadenColection;

interface PresenterInterface
{
    public function __construct(DataFacadenColection $dataFacade);

    public function present(): string;
}