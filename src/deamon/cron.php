#!/usr/bin/php
<?php
declare(strict_types=1);
set_time_limit(0);

use Modules\Internal\Enums\DebugModeEnum;
use Nmea\Cache\Memcached;
use Nmea\Config\Config;
use Nmea\Cron\CronWorker;
use Nmea\Database\Database;

require_once(__DIR__ . '/../../vendor/autoload.php');
Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());
$worker = new CronWorker(
        60,
        Database::getInstance(),
        new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()),
        getRunMode($argv)
);
$worker->attach(new Modules\Module\AnchorWatch\Bootstrap($worker->isDebug()));
$worker->attach(new Modules\Module\WeatherStatistic\Bootstrap());
$worker->attach(new Modules\Module\Logbook\Bootstrap());
$worker->run();


function getRunMode(array $argv):DebugModeEnum
{
    if (isset($argv[1])) {
        parse_str($argv[1], $output);
         if (isset($output['--help'])) {
             echo '--help' . PHP_EOL;
             echo '--version' . PHP_EOL;
             echo '--mode' . '= [debug|both]' . PHP_EOL;
             echo '     {debug} only print to terminal' . PHP_EOL;
             echo '     {both}  run normal and print to terminal' . PHP_EOL;

             exit;
         }
         if (isset($output['--version'])) {
             $version =  include  __DIR__ . '/../version.inc';
             echo 'Version ' . $version . PHP_EOL;

             exit;
         }
         if (isset($output['--mode'])) {
            switch ($output['--mode']) {
                case 'debug' :
                    echo 'mode=debug' . PHP_EOL;
                    return DebugModeEnum::DEBUG;
                case 'both' :
                    echo 'mode=both' . PHP_EOL;
                    return DebugModeEnum::NORMAL_PLUS_DEBUG;
                default:
                    return DebugModeEnum::NORMAL;
            }
         }
         echo '--help' . PHP_EOL;
         echo '--version' . PHP_EOL;
         echo '--mode' . '=[debug|both]' . PHP_EOL;

         exit;
    }

    return DebugModeEnum::NORMAL;
}
