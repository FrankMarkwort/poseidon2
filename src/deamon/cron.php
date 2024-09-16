#!/usr/bin/php
<?php
declare(strict_types=1);
set_time_limit(0);

use Modules\Internal\Enums\DebugModeEnum;
use Core\Cache\Memcached;
use Core\Config\Config;
use Core\Cron\CronWorker;
use Core\Database\Database;

require_once(__DIR__ . '/../../vendor/autoload.php');
$registerObserver = include (__DIR__ . '/../Modules/register.php');
Database::getInstance()->init(Config::getMariadbHost(), Config::getMariadbPort(),Config::getMariadbUser(),Config::getMariadbPassword(), Config::getMariadbName());
$worker = new CronWorker(
        60,
        Database::getInstance(),
        new Memcached(Config::getMemcacheHost(),Config::getMemcachePort()),
        getRunMode($argv)
);
$registerObserver[CronWorker::class]($worker);
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
