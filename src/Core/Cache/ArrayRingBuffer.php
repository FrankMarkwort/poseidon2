<?php
declare(strict_types=1);

namespace Core\Cache;

use Iterator;

class ArrayRingBuffer extends AbstractRingBuffer implements Iterator
{
    public function isFull():bool
    {
        return (count($this->getCache()->getAll()) ===  $this->getMax() && $this->getStoreIndex() === $this->getReadIndex());
    }

    protected function setValue(int $key, mixed $value):void
    {
        $this->getCache()->set((string)$key, $value);
    }

    protected function readValue(int $key):mixed
    {
        return $this->getCache()->get((string)$key);
    }

    protected function getReadIndex(): int
    {
        return intval($this->getCache()->getByKey('index', 'readIndex'));
    }

    protected function setReadIndex(int $index): void
    {
        $this->getCache()->setByKey('index', 'readIndex', (string)$index);
    }

    protected function getStoreIndex(): int
    {
        return intval($this->getCache()->getByKey('index', 'storeIndex'));
    }

    protected function setStoreIndex(int $index): void
    {
        $this->getCache()->setByKey('index', 'storeIndex', (string)$index);
    }

    protected function issetBuffer(int $key): bool
    {
        return ! is_null($this->getCache()->get((string) $key));
    }

    protected function countBuffer(): int
    {
        return count($this->getCache()->getAll());
    }

    public function current(): mixed
    {
        return $this->getValue();
    }

    public function next(): void
    {
        // dont need
    }

    public function key(): int
    {
        return $this->getReadIndex();
    }

    public function valid(): bool
    {
        return $this->issetBuffer($this->getReadIndex());
    }

    public function rewind(): void
    {
        // dont need
    }
}