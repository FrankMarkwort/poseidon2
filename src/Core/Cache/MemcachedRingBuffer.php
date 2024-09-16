<?php
declare(strict_types=1);

namespace Core\Cache;
class MemcachedRingBuffer extends AbstractRingBuffer
{
    private const string SERVER_KEY = 'index';
    private const string READ_INDEX = 'readIndex';
    private const string STORE_INDEX = 'storeIndex';
    public function isFull():bool
    {
        return ($this->count() ===  $this->getMax() && $this->getStoreIndex() === $this->getReadIndex());
    }

    private function count():int
    {
        return (count(array_filter($this->getCache()->getAll(), 'is_int', ARRAY_FILTER_USE_KEY)));
    }

    protected function setValue(int $key, mixed $value):void
    {
        $this->getCache()->set((string) $key, $value);
    }

    protected function readValue(int $key):mixed
    {
        return $this->getCache()->get((string)$key);
    }

    protected function getReadIndex(): int
    {
        return $this->getCache()->getByKey(self::SERVER_KEY, self::READ_INDEX);
    }

    protected function setReadIndex(int $index): void
    {
        $this->getCache()->setByKey(self::SERVER_KEY, self::READ_INDEX, $index);
    }

    protected function getStoreIndex(): int
    {
        return $this->getCache()->getByKey(self::SERVER_KEY, self::STORE_INDEX);
    }

    protected function setStoreIndex(int $index): void
    {
        $this->getCache()->setByKey(self::SERVER_KEY, self::STORE_INDEX, $index);
    }

    protected function issetBuffer(int $key): bool
    {
        return ! is_null($this->getCache()->get((string) $key));
    }

    protected function countBuffer(): int
    {
        return count($this->getCache()->getAll());
    }
}