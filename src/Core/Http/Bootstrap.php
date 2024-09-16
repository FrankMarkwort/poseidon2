<?php
declare(strict_types=1);

namespace Core\Http;

use Exception;
use Core\Cache\CacheInterface;
use Core\Config\ConfigException;
use Core\Logger\Factory;
use Core\Parser\Data\DataFacadenColection;
use Core\Parser\DataFacadeFactory;
use Core\Parser\ParserException;
use Core\View\Html;
use Core\View\Json;

readonly class Bootstrap
{

    public function __construct(private CacheInterface $cache)
    {
    }

    public function run(): void
    {
        try {
            if (isset($_GET['pgn'])) {
                $this->getOnePgn((int)$_GET['pgn']);
            } else {
                $this->getAllPgns();
            }
        } catch (Exception $e) {
             Factory::log($e->getMessage());
        }
    }

    /**
     * @throws ConfigException
     */
    private function getOnePgn(int $pgn):void
    {
        $nmea20000 = $this->cache->get((string)$pgn);
        $collection = null;
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

    /**
     * @throws ConfigException
     */
    private function getAllPgns():void
    {
        $collection = new DataFacadenColection();
        foreach ($this->cache->getAll() as $pgn => $nmea2000) {
            try {
                $collection->add(DataFacadeFactory::create($nmea2000, 'YACHT_DEVICE'));
            } catch (ParserException|ConfigException $e) {

            }
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