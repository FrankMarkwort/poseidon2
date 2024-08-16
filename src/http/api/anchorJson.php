<?php
error_reporting(E_ALL);
require_once( __DIR__ . '/../../../vendor/autoload.php');
use Nmea\Config\Config;
use Nmea\Cache\Memcached;
use \Nmea\Parser\DataFacadeFactory;
header('Content-Type: application/json; charset=utf-8');
$cache  = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
if ($cache->isSet('OBJ_ANCHOR')) {
    $ancor = unserialize($cache->get('OBJ_ANCHOR'));
    echo $ancor->toJson(JSON_PRETTY_PRINT);
} else  {
    $nmea20000 = $cache->get(129025);
    $dataFacade = DataFacadeFactory::create($nmea20000, 'YACHT_DEVICE');
    $latitude = $dataFacade->getFieldValue(1)->getValue();
    $longitude = $dataFacade->getFieldValue(2)->getValue();
    $array = [
        "latitude" =>  $latitude,
        "longitude" => $longitude
    ];
    if ($cache->isSet('chain_length')) {
        $array['chainLength'] = $cache->get('chain_length');
    }
    echo json_encode($array);
}
