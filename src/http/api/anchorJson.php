<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once( __DIR__ . '/../../../vendor/autoload.php');

use Modules\External\FromCache\AnchorFacade;
use Modules\Module\Cron\AnchorWatch\Anchor;
use Core\Cache\Memcached;
use Core\Config\Config;

header('Content-Type: application/json; charset=utf-8');
try {
    $cache  = new Memcached(Config::getMemcacheHost(), Config::getMemcachePort());
    if ($cache->isSet('OBJ_ANCHOR')) {
        $ancor = unserialize($cache->get('OBJ_ANCHOR'));
        echo $ancor->toJson(JSON_PRETTY_PRINT);

    } else {
        /*
        $gpsFacade = DataFacadeFactory::create($cache->get('129025'), 'YACHT_DEVICE');
        $windFacade = DataFacadeFactory::create($cache->get('130306'), 'YACHT_DEVICE');
        $headingFacade = DataFacadeFactory::create($cache->get('127250'), 'YACHT_DEVICE');
        $waterDepthFacade = DataFacadeFactory::create($cache->get('128267'), 'YACHT_DEVICE');
        $waterDepth = $waterDepthFacade->getFieldValue(2)->getValue() + $waterDepthFacade->getFieldValue(1)->getValue();
        $awaDeg = $windFacade->getFieldValue(3)->getValue();
        $aws = $windFacade->getFieldValue(2)->getValue();
        $headingDeg = $headingFacade->getFieldValue(2)->getValue();
        $latitude = $gpsFacade->getFieldValue(1)->getValue();
        $longitude = $gpsFacade->getFieldValue(2)->getValue();
        */
        $anchorFacade = new AnchorFacade($cache);
        if ($cache->isSet('chain_length')) {
            $chainLength = intval($cache->get('chain_length'));
        } else {
            $chainLength = 0;
        }
        echo json_encode([
            'ext' => false,
            'base' => Anchor::toBaseArray(
                $anchorFacade->getLatitudeDeg(),
                $anchorFacade->getLongitudeDeg(),
                $anchorFacade->getHeadingDeg(),
                $anchorFacade->getAwaRad(),
                $anchorFacade->getAws(),
                $anchorFacade->getWaterDepth(),
                $chainLength,
                false
            )
        ]);
     }
} catch (Exception $e) {
    echo json_encode([
            'ext' => false,
            'base' => false,
            'error' => $e->getMessage()
        ]);
}
