<?php
declare(strict_types=1);

namespace Core\Cache;

use Modules\Internal\Interfaces\CacheInterface;

abstract class AbstractRingBuffer
{
    public function __construct(protected CacheInterface $cache, protected readonly int $max = 60)
    {
        $this->setStoreIndex(0);
        $this->setReadIndex(0);
    }
    public function addValue(mixed $value):self
    {
        $this->setValue($this->getStoreIndex(), $value);
        if ($this->isFull()) {
            $this->increaseReadIndex();
        }

        $this->increaseStoreIndex();

        return $this;
    }
    public function isFull():bool
    {
        return ($this->countBuffer() ===  $this->getMax() && $this->getStoreIndex() === $this->getReadIndex());
    }

    public function getValue():mixed
    {
        if (! $this->issetBuffer($this->getReadIndex()) || is_null($this->readValue($this->getReadIndex()))) {

            return null;
        }
        $result = $this->readValue($this->getReadIndex());
        $this->setValue($this->getReadIndex(), null);
        $this->increaseReadIndex();

        return $result;
    }

    abstract protected function issetBuffer(int $key):bool;
    abstract protected function countBuffer():int;

    abstract protected function setValue(int $key, mixed $value):void;

    abstract protected function readValue(int $key):mixed;

    abstract protected function getReadIndex():int;

    abstract protected function setReadIndex(int $index):void;

    abstract protected function getStoreIndex():int;

    abstract protected function setStoreIndex(int $index):void;

    protected function increaseReadIndex():void
    {
        $readIndex = $this->getReadIndex();
        $readIndex++;
        $readIndex %= $this->getMax();
        $this->setReadIndex($readIndex);
    }

    protected function increaseStoreIndex():void
    {
        $storeIndex = $this->getStoreIndex();
        $storeIndex++;
        $storeIndex %= $this->getMax();
        $this->setStoreIndex($storeIndex);
    }

    protected function getCache():CacheInterface
    {
        return $this->cache;
    }

    protected function getMax():int
    {
        return $this->max;
    }
}