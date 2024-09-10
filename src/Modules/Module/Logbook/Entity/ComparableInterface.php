<?php
declare(strict_types=1);

namespace Modules\Module\Logbook\Entity;

interface ComparableInterface
{
    public function compareTo(ComparableInterface $position):bool;
}