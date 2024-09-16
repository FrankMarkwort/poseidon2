<?php
declare(strict_types=1);

namespace Core\Parser\Data;

use Core\Config\ConfigException;

readonly class DataFacade
{
    public function __construct(private MainPart $mainPart, private DataPart $dataPart)
    {
    }

    public function getTimestamp(): string
    {
        return $this->mainPart->getTimestamp();
    }

    public function getFrameType():string
    {
        return $this->mainPart->getFrameTye();
    }

    public function getPrio(): int
    {
        return $this->mainPart->getPrio();
    }

    public function getPng(): int
    {
        return $this->mainPart->getPng();
    }

    public function getDataPage(): ?int
    {
        return $this->mainPart->getDataPage();
    }

    public function getSrc(): int
    {
        return $this->mainPart->getSrc();
    }

    public function getDst(): int
    {
        return $this->mainPart->getDst();
    }

    public function getLength(): int
    {
        return $this->mainPart->getLength();
    }

    public function count(): int
    {
        return $this->dataPart->count();
    }

    /**
     * @throws ConfigException
     */
    public function getDescription(): string
    {
        return $this->dataPart->getDescription();
    }

    /**
     * @throws ConfigException
     */
    public function getFieldValue(int $order): Data
    {
        return $this->dataPart->getFieldValue($order);
    }

    /**
     * @throws ConfigException
     */
    public function getOrderIds(): array
    {
        return $this->dataPart->getOrderIds();
    }

    public function getPduFormat():int
    {
        return $this->mainPart->getPduFormat();
    }
}
