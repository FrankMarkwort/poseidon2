<?php

namespace Nmea\Protocol\Socket;

use \Countable;
use \Iterator;
use \Socket;
use \ArrayAccess;

class SocketsCollection  implements  Iterator, Countable, ArrayAccess
{
    private array $clientSockets;

    public function current(): mixed
    {
        return current($this->clientSockets);
    }

    public function next(): void
    {
        next($this->clientSockets);
    }

    public function key(): int
    {
        return key($this->clientSockets);
    }

    public function valid(): bool
    {
        return isset($this->clientSockets[key($this->clientSockets)]);
    }

    public function rewind(): void
    {
        reset($this->clientSockets);
    }

    public function count(): int
    {
        return count($this->clientSockets);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->clientSockets[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->clientSockets[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (! $value instanceof Socket) {
            throw new \InvalidArgumentException('Offset must be an instance of Socket');
        }
        if( is_null($offset)) {
            $this->clientSockets[] = $value;
        } else {
            $this->clientSockets[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->clientSockets[$offset]);
    }

    public function toArray():array
    {
        return $this->clientSockets;
    }
}