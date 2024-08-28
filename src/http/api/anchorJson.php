<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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
    $gpsFacade = DataFacadeFactory::create($cache->get(129025),'YACHT_DEVICE');
    $windFacade = DataFacadeFactory::create($cache->get(130306),'YACHT_DEVICE');
    $headingFacade = DataFacadeFactory::create($cache->get(127250),'YACHT_DEVICE');
    $awa = $windFacade->getFieldValue(3)->getValue();
    $aws = $windFacade->getFieldValue(2)->getValue();
    $heading = $headingFacade->getFieldValue(2)->getValue();
    $latitude = $gpsFacade->getFieldValue(1)->getValue();
    $longitude = $gpsFacade->getFieldValue(2)->getValue();
    $array = [
        "latitude" =>  $latitude,
        "longitude" => $longitude
    ];
    $array['awaLine'] = getLine( deg2rad($latitude), deg2rad($longitude), fmod($awa, pi()) + $heading, intval($aws * 1.943844));
    if ($cache->isSet('chain_length')) {
        $array['chainLength'] = $cache->get('chain_length');
    }

    echo json_encode($array);
}
function getLine($latitudeRad, $longitudeRad, $angleRad, $length): array
    {
        $distance = $length / 6378136.6;
        $lat2 = asin(sin($latitudeRad) * cos($distance) + cos($latitudeRad) * sin($distance) * cos($angleRad));
        $lon2 = $longitudeRad + atan2(sin($angleRad) * sin($distance) * cos($longitudeRad), cos($distance - sin($latitudeRad) * sin($lat2)));

        return [[rad2deg($longitudeRad), rad2deg($latitudeRad)],[rad2deg($lon2), rad2deg($lat2)]];
    }
