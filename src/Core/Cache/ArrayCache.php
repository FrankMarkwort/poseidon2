<?php
declare(strict_types=1);

namespace Core\Cache;

use Modules\Internal\Interfaces\CacheInterface;

class ArrayCache implements CacheInterface
{

    private array $array = [];
    private array $arrayByKey =[];

    public function get(string $pgn):mixed
    {
        return $this->array[$pgn];
    }

    public function set(string $pgn, mixed $value):self
    {
        $this->array[$pgn] = $value;

        return $this;
    }

    public function getAll(): array
    {
        return $this->array;
    }

    public function clear():self
    {
        $this->array = [];

        return $this;
    }

    public function setByKey(string $serverKey, string $key, mixed $value, int $expiration = 0): self
    {
        $this->arrayByKey[$serverKey][$key] = $value;

        return $this;
    }

    public function getByKey(string $serverKey, string $key, ?callable $cacheCb = null, int $getFlags = 0): mixed
    {
        return $this->arrayByKey[$serverKey][$key] ?? null;
    }

    public function isSet(string $key): bool
    {
        return isset($this->array[$key]);
    }

    public function delete(string $key):bool
    {
        unset($this->array[$key]);

        return true;
    }
}