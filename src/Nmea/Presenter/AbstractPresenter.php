<?php

namespace Nmea\Presenter;

use Nmea\Parser\Data\DataFacade;
use Nmea\Parser\Data\DataFacadenColection;
use Nmea\Parser\Lib\Units\Unit;

abstract class AbstractPresenter implements PresenterInterface
{
    public function __construct(protected readonly DataFacadenColection $dataFacaden)
    {
    }

    abstract public function present(): string;

   protected function getValueWithUnit(DataFacade $dataFacade, int $orderId):string|null
   {
        if (is_numeric($dataFacade->getFieldValue($orderId)->getValue()) && is_string($dataFacade->getFieldValue($orderId)->getUnit())) {
            return (new Unit(
                $dataFacade->getFieldValue($orderId)->getValue(),
                $dataFacade->getFieldValue($orderId)->getUnit(),
                $dataFacade->getFieldValue($orderId)->getType()
            ))->getMappedValueWithUnit();
        }

        return $dataFacade->getFieldValue($orderId)->getValue();
    }
}