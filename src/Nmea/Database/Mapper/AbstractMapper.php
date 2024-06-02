<?php

namespace Nmea\Database\Mapper;

use Nmea\Database\DatabaseInterface;

abstract class AbstractMapper
{
    public function __construct(protected readonly DatabaseInterface $database)
    {
    }

    protected function getDatabase(): DatabaseInterface
    {
        return $this->database;
    }

}
