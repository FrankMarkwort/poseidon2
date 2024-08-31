<?php
declare(strict_types=1);

namespace Nmea\Parser\Lib\Units;

interface UnitInterface
{
    public const string SI_ANGEL = 'rad';
    public const string SI_DEGRE = 'deg';
    public const string SI_TEMPERATURE = 'K';
    public const string SI_SPEED = 'm/s';
    public const string SI_ANGEL_SECOND = 'rad/s';
    public const string SI_KELVIN = 'K';

    public const string SI_CELSIUS = '°C';

    public const string SI_PASCAL = 'Pa';
    public const string SI_DEGRE_SECOND = 'deg/s';
    public const string SI_METRE = 'm';
    public const string SI_SECONDS = 's';

    public const string SI_GES_GRAD = 'grad';
    public const string SI_GES_DAY = 'd';
    public const string PERCENT = '%';
    public const string LITRE = 'L';
    public const string DECIBEL = 'dB';
    public const string HEKTOPASCAL = 'hPa';
    public const string VOLT = 'V';
    public const string AMPERE = 'A';
    public const string DEKAPASCAL = 'dPa';
    public const string LITRE_HOUR = 'L/h';
    public const string REACTIVE_POWER = 'var';
    public const string WATT = 'W';
    public const string VOLT_AMPERE = 'VA';
    public const string DAY = 'days';
    public const string HERTZ = 'Hz';
    public const string KILOWAT_HOUR = 'kWh';

    public const string UNIT = 'unit';
    public const string ROUND = 'round';
    public const string PRINT = 'print';

}
