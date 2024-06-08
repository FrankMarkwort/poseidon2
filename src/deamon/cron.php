#!/usr/bin/php
<?php
set_time_limit(0);
use Nmea\Cache\Memcached;
use Nmea\Database\Database;
use Nmea\Config\Config;
use Nmea\Cron\CronWorker;
use Nmea\Cron\ModeEnum;

require_once(__DIR__ . '/../../vendor/autoload.php');
Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());
$worker = new CronWorker(
        60,
        Database::getInstance(),
        new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()),
        getRunMode($argv)
);
$worker->run();

function getRunMode(array $argv):ModeEnum
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
                    return ModeEnum::DEBUG;
                case 'both' :
                    echo 'mode=both' . PHP_EOL;
                    return ModeEnum::NORMAL_PLUS_DEBUG;
                default:
                    return ModeEnum::NORMAL;
            }
         }
         echo '--help' . PHP_EOL;
         echo '--version' . PHP_EOL;
         echo '--mode' . '=[debug|both]' . PHP_EOL;

         exit;
    }

    return ModeEnum::NORMAL;
}
