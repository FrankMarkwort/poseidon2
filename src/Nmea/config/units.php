<?php
use Nmea\Parser\Lib\Units\UnitInterface;

return  [
    UnitInterface::SI_SPEED => [UnitInterface::UNIT => 'kt', UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s kt'],
    UnitInterface::SI_ANGEL => [UnitInterface::UNIT => UnitInterface::SI_GES_GRAD, UnitInterface::ROUND => 1, UnitInterface::PRINT => '%s°'],
    UnitInterface::SI_KELVIN => [UnitInterface::UNIT => 'C', UnitInterface::ROUND => 2,UnitInterface::PRINT => '%s °C'],
    UnitInterface::SI_GES_DAY => [UnitInterface::UNIT => 'day', UnitInterface::ROUND => 2,UnitInterface::PRINT => '%s'],
    UnitInterface::SI_METRE => [UnitInterface::UNIT => UnitInterface::SI_METRE, UnitInterface::ROUND => 2,UnitInterface::PRINT => '%s %s'],
    UnitInterface::SI_SECONDS => [UnitInterface::UNIT => UnitInterface::SI_SECONDS, UnitInterface::ROUND => 0,UnitInterface::PRINT => '%s'],
    UnitInterface::SI_PASCAL => [UnitInterface::UNIT => 'hPa', UnitInterface::ROUND => 2,UnitInterface::PRINT => '%s hPa'],
    'dB' => [UnitInterface::UNIT => 'dB', UnitInterface::ROUND => 2,UnitInterface::PRINT => '%s dB'],
    'deg' => [UnitInterface::UNIT => 'deg', UnitInterface::ROUND => 2,UnitInterface::PRINT => "%s° %s' %s''%s" ]
];