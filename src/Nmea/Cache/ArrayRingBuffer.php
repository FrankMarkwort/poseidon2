<?php

namespace Nmea\Cache;

class ArrayRingBuffer extends AbstractRingBuffer
{
    public function isFull():bool
    {
        return (count($this->getCache()->getAll()) ===  $this->getMax() && $this->getStoreIndex() === $this->getReadIndex());
    }

    protected function setValue(string $key, mixed $value):void
    {
        $this->getCache()->set($key, $value);
    }

    protected function readValue(string $key):mixed
    {
        return $this->getCache()->get($key);
    }

    protected function getReadIndex(): int
    {
        return $this->getCache()->getByKey('index', 'readIndex');
    }

    protected function setReadIndex(int $index): void
    {
        $this->getCache()->setByKey('index', 'readIndex', $index);
    }

    protected function getStoreIndex(): int
    {
        return $this->getCache()->getByKey('index', 'storeIndex');
    }

    protected function setStoreIndex(int $index): void
    {
        $this->getCache()->setByKey('index', 'storeIndex', $index);
    }

    protected function issetBuffer(int $key): bool
    {
        return ! is_null($this->getCache()->get($key));
    }

    protected function countBuffer(): int
    {
        return count($this->getCache()->getAll());
    }
}