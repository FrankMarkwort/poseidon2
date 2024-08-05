<?php

namespace Nmea\Deamon;

use Nmea\Cache\CacheInterface;
use Nmea\Logger\Factory;
use Nmea\Protocol\FramesFactory;
use Nmea\Protocol\Socket\Client;

class Bootstrap
{
    public function __construct(readonly Serial $serial, readonly CacheInterface $cache, readonly Client $websocket)
    {
    }

    public function run()
    {
        $run = true;
        $this->serial->open();
        FramesFactory::setCache($this->cache);
        FramesFactory::setSocket($this->websocket);
        do {
            try {
                $line = $this->serial->readStream();
                if (! $this->isValidNmea2000($line)) {
                    #Factory::log('Deamon\Bootstrap skipped invalid stram data: ' . $line);
                    continue;
                }

                FramesFactory::addData($line);
            } catch (\Exception $e) {
                Factory::log($line . ': ' . $e->getMessage());
                $run = false;
            }
        } while ($run);

        $this->serial->close();
    }

     private function isValidNmea2000(string $nmea2000)
     {
        $result = preg_match('/^(\d\d:\d\d:\d\d\.\d\\d\d \w \w{8} \w\w \w\w \w\w \w\w \w\w \w\w \w\w \w\w)/', $nmea2000);
        if ($result > 0) return true;

        return false;
     }
}
