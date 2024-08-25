<?php

namespace Nmea\Http;

use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Logger\Factory;
use Nmea\Parser\Data\DataFacadenColection;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\ParserException;
use Nmea\View\Html;
use Nmea\View\Json;

class Bootstrap
{

    public function __construct(private readonly CacheInterface $cache)
    {
    }

    public function run(): void
    {
        try {
            if (isset($_GET['pgn'])) {
                $this->getOnePgn($_GET['pgn']);
            } else {
                $this->getAllPgns();
            }
        } catch (\Exception $e) {
             Factory::log($e->getMessage());
        }
    }

    private function getOnePgn(int $pgn):void
    {
        $nmea20000 = $this->cache->get($pgn);
        try {
            $collection = new DataFacadenColection();
            $collection->add(DataFacadeFactory::create($nmea20000, 'YACHT_DEVICE'));
        } catch (ParserException $e) {}
        if ($this->getmode() === 'json') {
            echo (new Json($collection))->present();
        } else {
            echo (new Html($collection))->present();
        }
    }

    private function getAllPgns():void
    {
        $collection = new DataFacadenColection();
        foreach ($this->cache->getAll() as $pgn => $nmea2000) {
            try {
                $collection->add(DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE'));
            } catch (ParserException $e) {

            } catch (ConfigException $f) {}
        }
        if ($this->getmode() === 'json') {
            echo (new Json($collection))->present();
        } else {
            echo (new Html($collection))->present();
        }
    }
    private function getPgn():?int
    {
        if (isset($_GET['pgn'])) {

           return (int) $_GET['pgn'];
        }

        return null;
    }

    private function getmode():?string
    {
        if (isset($_GET['mode'])) {

            return $_GET['mode'];
        }

        return 'html';
    }
}