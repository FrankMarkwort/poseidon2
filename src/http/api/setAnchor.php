<?php
declare(strict_types=1);
require_once( __DIR__ . '/../../../vendor/autoload.php');
use Nmea\Config\Config;
use Nmea\Cache\Memcached;

$cache  = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());

if (getModeSet()) {
    $cache->set('chain_length', getChainLength());
    echo 'set';
} else {
    $cache->delete('chain_length');
    $cache->delete('OBJ_ANCHOR');
    echo 'Delete';
}

echo json_encode([
        "latitude" =>  37.128886,
        "longitude" => 26.8533284,
        "chainLength" => $cache->get('chain_length')
]);

function getModeSet():bool
{
    if(isset($_GET['set']) && $_GET['set'] === 'true') {

        return true;
    }

    return false;
}

function getChainLength():int
{
    if(isset($_GET['meter']) && is_numeric($_GET['meter'])) {

        return intval($_GET['meter']);
    }
    return 0;
}
