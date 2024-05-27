<?php

namespace Nmea\Cache;

interface CacheInterface
{
    public function get(string $pgn):mixed;

    public function set(string $pgn, mixed $value):self;

    public function setByKey(string $serverKey, string $key, mixed $value, int $expiration = 0):self;

    public function getByKey(string $serverKey, string $key, ?callable $cacheCb = null, int $getFlags = 0): mixed;

    public function getAll():array;

    public function clear():self;
}