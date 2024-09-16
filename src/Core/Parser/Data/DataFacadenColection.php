<?php
declare(strict_types=1);

namespace Core\Parser\Data;

use Countable;
use Iterator;

class DataFacadenColection implements Iterator, Countable
{
    private array $dataFacaden = [];
    public function add(DataFacade $dataFacade):self
    {
        $this->dataFacaden[] = $dataFacade;

        return $this;
    }

    # [\ReturnTypeWillChange]
    public function current(): DataFacade
    {
        return current($this->dataFacaden);
    }

    public function next(): void
    {
        next($this->dataFacaden);
    }

    # [\ReturnTypeWillChange]
    public function key(): int
    {
        return key($this->dataFacaden);
    }

    public function valid(): bool
    {
        return key($this->dataFacaden) !== null;
    }

    public function rewind(): void
    {
        reset($this->dataFacaden);
    }

    public function count():int
    {
        return count($this->dataFacaden);
    }
}