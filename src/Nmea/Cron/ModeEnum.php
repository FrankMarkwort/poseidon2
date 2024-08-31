<?php
declare(strict_types=1);

namespace Nmea\Cron;

enum ModeEnum
{
    case NORMAL;
    case DEBUG;
    case NORMAL_PLUS_DEBUG;
}
