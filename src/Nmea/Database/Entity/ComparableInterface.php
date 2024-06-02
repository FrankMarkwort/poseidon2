<?php

namespace Nmea\Database\Entity;

interface ComparableInterface
{
    public function compareTo(ComparableInterface $position):bool;
}