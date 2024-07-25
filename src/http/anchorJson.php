<?php
require_once( __DIR__ . '/../../vendor/autoload.php');
use Nmea\Config\Config;
use Nmea\Cache\Memcached;
header('Content-Type: application/json; charset=utf-8');
$cache  = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
if ($cache->isSet('OBJ_ANCHOR')) {
    $ancor = unserialize($cache->get('OBJ_ANCHOR'));
    echo $ancor->toJson(JSON_PRETTY_PRINT);
} else  {
    echo '[]';
}
