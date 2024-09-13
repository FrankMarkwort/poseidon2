<?php
declare(strict_types=1);

namespace Nmea\Deamon;

use Exception;
use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\Interfaces\InterfaceObserverRealtime;
use Modules\Internal\RealtimeDistributor;
use Nmea\Cache\CacheInterface;
use Nmea\Logger\Factory;
use Nmea\Protocol\FramesFactory;
use Nmea\Protocol\Socket\Client;

readonly class Bootstrap
{
    public function __construct(public Serial $serial, public CacheInterface $cache, public Client $websocket, protected RealtimeDistributor $distributor)
    {
    }

    public function run(): void
    {
        $run = true;
        $line = '';
        $this->serial->open();
        FramesFactory::setCache($this->cache);
        FramesFactory::setSocket($this->websocket);
        do {
            try {
                $line = $this->serial->readStream();
                if (! $this->isValidNmea2000($line)) {

                    continue;
                }

                FramesFactory::addData($line, $this->distributor);
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

    public function attach(InterfaceObserverRealtime $observer)
    {
        // TODO: Implement attach() method.
    }

    public function detach(InterfaceObserverRealtime $observer)
    {
        // TODO: Implement detach() method.
    }

    public function notify(): void
    {
        // TODO: Implement notify() method.
    }
}
