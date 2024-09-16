<?php
declare(strict_types=1);

namespace Core\Database\Mapper;

use Core\Database\DatabaseInterface;

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
