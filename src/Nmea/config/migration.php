<?php
const MIG = 'migration';
const FILE = 'file';
return [
    0 => [MIG => 'create_table_wind_speed_minute' , FILE => __DIR__ . '/migrations/create_table_wind_speed_minute.sql' ],
    1 => [MIG => 'alter_table_wind_speed_minute_add_wather_temperature' , FILE => __DIR__ . '/migrations/alter_table_wind_speed_minute_add_wather_temperature.sql' ],
];

