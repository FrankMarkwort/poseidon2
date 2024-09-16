<?php
declare(strict_types=1);

namespace Core\View;

use Core\Config\ConfigException;
use Core\Parser\Data\DataFacade;
use Core\Parser\Data\DataFacadenColection;
use Core\Parser\Lib\Units\Unit;

abstract class AbstractView implements ViewInterface
{
    public function __construct(protected readonly DataFacadenColection $dataFacaden)
    {
    }

    abstract public function present(): string;

    /**
     * @throws ConfigException
     */
    protected function getValueWithUnit(DataFacade $dataFacade, int $orderId):string|null
    {
        if (is_numeric($dataFacade->getFieldValue($orderId)->getValue()) && is_string($dataFacade->getFieldValue($orderId)->getUnit())) {
            return (new Unit(
                $dataFacade->getFieldValue($orderId)->getValue(),
                $dataFacade->getFieldValue($orderId)->getUnit(),
                $dataFacade->getFieldValue($orderId)->getType()
            ))->getMappedValueWithUnit();
        }

        return (string) $dataFacade->getFieldValue($orderId)->getValue();
    }
}