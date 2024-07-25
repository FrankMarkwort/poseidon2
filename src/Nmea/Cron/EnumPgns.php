<?php

namespace Nmea\Cron;

enum EnumPgns: string
{
    case WIND = '130306';
    case COG_SOG ='129026';
    case Vessel_Heading = '127250';
    case Set_And_Drift = '129291';
    case Position =	'129025';
    case Temperature = '130312';
    case Water_Depth = '128267';
}
