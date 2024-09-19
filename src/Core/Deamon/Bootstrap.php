<?php
declare(strict_types=1);

namespace Core\Deamon;

use Core\Logger\Factory;
use Core\Protocol\FramesFactory;
use Core\Protocol\Socket\Client;
use Exception;
use Modules\Internal\Interfaces\CacheInterface;
use Modules\Internal\Interfaces\InterfaceObservableRealtime;

readonly class Bootstrap
{
    public function __construct(public Serial $serial, public CacheInterface $cache, public Client $websocket, protected InterfaceObservableRealtime $distributor)
    {
    }

    public function run(): void
    {
        $run = true;
        $line = '';
        $this->serial->open();
        FramesFactory::setCache($this->cache);
        FramesFactory::setSocket($this->websocket);
        FramesFactory::setRealtimeDistributor($this->distributor);
        do {
            try {
                $line = $this->serial->readStream();
                if (! $this->isValidNmea2000($line)) {

                    continue;
                }

                FramesFactory::addData($line);
            } catch (Exception $e) {
                Factory::log($line . ': ' . $e->getMessage());
                echo $e->getMessage() . $e->getTraceAsString(). PHP_EOL;
                $run = false;
            }
        } while ($run);

        $this->serial->close();
    }

     private function isValidNmea2000(string $nmea2000): bool
     {
        $result = preg_match('/^(\d\d:\d\d:\d\d\.\d\\d\d \w \w{8} \w\w \w\w \w\w \w\w \w\w \w\w \w\w \w\w)/', $nmea2000);
        if ($result > 0) return true;

        return false;
     }
}
