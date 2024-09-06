<?php
declare(strict_types=1);

namespace Nmea\Cron;

enum EnumPgns: string
{
    case WIND = '130306';
    case COG_SOG ='129026';
    case VESSEL_HEADING = '127250';
    case SET_AND_DRIFT = '129291';
    case POSITION =	'129025';
    case TEMPERATURE = '130312';
    case WATER_DEPTH = '128267';
}
