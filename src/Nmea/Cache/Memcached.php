<?php

namespace Nmea\Cache;

use Memcached as Cache;
use Nmea\Logger\Factory;
use Exception;

class Memcached implements CacheInterface
{
    private $cache;

    /**
     * @throws Exception
     */
    public function __construct(private string $host = '127.0.0.1', private int $port = 11211)
    {
        $this->cache = new Cache();
        if (!$this->cache->addServer($host, $port)) {
            throw new Exception('memcache connection failed');
        }
    }
    public function isSet(string $key):bool
    {
        return $this->cache->get($key) !== false || $this->cache->getResultCode() != Cache::RES_NOTFOUND;
    }

    public function get(string $pgn): mixed
    {
        return $this->cache->get($pgn);
    }

    public function set(string $pgn, mixed $value): CacheInterface
    {
        $this->cache->set($pgn, $value);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array
    {
        $memcachedAllKeysNotRealyWorking = $this->getAllKeys();

        $allKeys = include(__DIR__ . '/../config/listPgns.php');
        if (is_array($memcachedAllKeysNotRealyWorking)) {
            $allKeys = array_merge($allKeys, $memcachedAllKeysNotRealyWorking);
        }

        if (empty($memcachedAllKeysNotRealyWorking)) {
            Factory::log('Memcached: getAllKeys return no result');
        }

        $this->cache->getDelayed($allKeys);
        $result = $this->cache->fetchAll();
        if ($result === false) {
            Factory::log('Memcached: FetchAll give no result');

            return [];
        }
        $resultArray = [];
        foreach ($result as $value) {
            $resultArray[$value['key']] = $value['value'];
        }

        return $resultArray;
    }

    public function clear(): self
    {
        $this->cache->flush();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getAllKeys(): array
    {
        $allKeys = [];
        $sock = fsockopen($this->host, $this->port, $errno, $errstr);
        if ($sock === false) {
            throw new Exception("Error connection to server {$this->host} on port {$this->port}: ({$errno}) {$errstr}");
        }

        if (fwrite($sock, "stats items\n") === false) {
            throw new Exception("Error writing to socket");
        }

        $slabCounts = [];
        while (($line = fgets($sock)) !== false) {
            $line = trim($line);
            if ($line === 'END') {
                break;
            }

            // STAT items:8:number 3
            if (preg_match('!^STAT items:(\d+):number (\d+)$!', $line, $matches)) {
                $slabCounts[$matches[1]] = (int)$matches[2];
            }
        }

        foreach ($slabCounts as $slabNr => $slabCount) {
            if (fwrite($sock, "lru_crawler metadump {$slabNr}\n") === false) {
                throw new Exception('Error writing to socket');
            }

            $count = 0;
            while (($line = fgets($sock)) !== false) {
                $line = trim($line);
                if ($line === 'END') {
                    break;
                }

                // key=foobar exp=1596440293 la=1596439293 cas=8492 fetch=no cls=24 size=14908
                if (preg_match('!^key=(\S+)!', $line, $matches)) {
                    $allKeys[] = $matches[1];
                    $count++;
                }
            }
        }

        if (fclose($sock) === false) {
            throw new Exception('Error closing socket');
        }
        return $allKeys;
    }

    public function setByKey(string $serverKey, string $key, mixed $value, int $expiration = 0): CacheInterface
    {
        $this->cache->setByKey($serverKey, $key, $value, $expiration);

        return $this;
    }

    public function getByKey(string $serverKey, string $key, ?callable $cacheCb = null, int $getFlags = 0): mixed
    {
        return $this->cache->getByKey($serverKey, $key, $cacheCb, $getFlags);
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }
}