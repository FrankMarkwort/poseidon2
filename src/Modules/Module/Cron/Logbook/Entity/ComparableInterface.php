<?php
declare(strict_types=1);

namespace Modules\Module\Cron\Logbook\Entity;

interface ComparableInterface
{
    public function compareTo(ComparableInterface $position):bool;
}