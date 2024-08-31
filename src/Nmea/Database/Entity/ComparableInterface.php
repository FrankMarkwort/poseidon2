<?php
declare(strict_types=1);

namespace Nmea\Database\Entity;

interface ComparableInterface
{
    public function compareTo(ComparableInterface $position):bool;
}