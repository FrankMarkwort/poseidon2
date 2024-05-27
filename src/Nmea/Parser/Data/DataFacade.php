<?php

namespace Nmea\Parser\Data;

use Nmea\Parser\Lib\Units\Unit;

class DataFacade
{
    public function __construct(private MainPart $mainPart, private DataPart $dataPart)
    {
    }

    public function getTimestamp(): string
    {
        return (string)$this->mainPart->getTimestamp();
    }

    public function getFrameType():string
    {
        return $this->mainPart->getFrameTye();
    }

    public function getPrio(): int
    {
        return (int)$this->mainPart->getPrio();
    }

    public function getPng(): int
    {
        return (int)$this->mainPart->getPng();
    }

    public function getDataPage(): ?int
    {
        return $this->mainPart->getDataPage();
    }

    public function getSrc(): int
    {
        return (int)$this->mainPart->getSrc();
    }

    public function getDst(): int
    {
        return (int)$this->mainPart->getDst();
    }

    public function getLength(): int
    {
        return (int)$this->mainPart->getLength();
    }

    public function count(): int
    {
        return $this->dataPart->count();
    }

    public function getDescription(): string
    {
        return $this->dataPart->getDescription();
    }

    public function getFieldValue(int $order): Data
    {
        return $this->dataPart->getFieldValue($order);
    }

    public function getOrderIds(): array
    {
        return $this->dataPart->getOrderIds();
    }

    public function getPduFormat()
    {
        return $this->mainPart->getPduFormat();
    }


}
