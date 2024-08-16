<?php
return [
    'develop' => [
        'memcached' => [
            'host' => '172.17.0.1',
            'port' => 11211
        ],
        'mariadb' => [
            'host' => '172.17.0.1',
            'port' => 3306,
            'user' => 'nmea2000',
            'password' => 'nmea2000',
            'dbname' => 'nmea2000'
        ],
        'socketServer' => [
            'host' => '172.17.0.1',
            'port' => 8090,
        ],
        'serialdevice' => __DIR__ . '/../../test/TestData/data.log',
    ],
    'production' => [
        'memcached' => [
            'host' => '127.0.0.1',
            'port' => 11211
        ],
        'mariadb' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'nmea2000',
            'password' => 'nmea2000',
            'dbname' => 'nmea2000'
        ],
        'socketServer' => [
            'host' => '192.168.0.101',
            'port' => 8090,
        ],
        'serialdevice' => '/dev/ttyACM0',
    ],
    'testing' => [
        'memcached' => [
            'host' => '127.0.0.1',
            'port' => 11211
        ],
        'mariadb' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'nmea2000',
            'password' => 'nmea2000',
            'dbname' => 'nmea2000'
        ],
        'socketServer' => [
            'host' => '172.17.0.1',
            'port' => 8090,
        ],
        'serialdevice' => '/dev/ttyACM0',
    ],
    'staging' => [
        'memcached' => [
            'host' => '127.0.0.1',
            'port' => 11211
        ],
        'mariadb' => [
            'host' => '127.0.0.1',
            'port' => 3306,
            'user' => 'nmea2000',
            'password' => 'nmea2000',
            'dbname' => 'nmea2000'
        ],
        'socketServer' => [
            'host' => '192.168.0.101',
            'port' => 8090,
        ],
        'serialdevice' => '/dev/ttyACM0',
    ]
];