<?php

namespace Modules\Internal\Pgns;

use Core\Config\ConfigException;
use Core\Parser\Data\DataFacade;
use Core\Parser\DataFacadeFactory;
use Core\Parser\ParserException;
use Modules\Internal\Interfaces\CacheInterface;

abstract class AbstractPgn
{
    public function __construct(protected CacheInterface $cache, protected bool $printToConsole = false)
    {
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     */
    protected function getFacade():DataFacade
    {
        $dataFacade =  DataFacadeFactory::create($this->getNmeaData(), 'YACHT_DEVICE');
        if ($this->isDebug()) {

            echo $this->printAllFieldNames($dataFacade);
        }

        return $dataFacade;
    }

    protected function isDebug():bool
    {
        return $this->printToConsole;
    }

    protected function getCache():CacheInterface
    {
        return $this->cache;
    }

    abstract protected function getNmeaData():string;

    /**
     * @throws ConfigException
     */
    protected function printAllFieldNames(DataFacade $dataFacade):string
    {
        $result = sprintf("%s pgn => %s src => %s  dst => %s  type =>  %s  pduFormat => %s dataPage => %s ",
            $dataFacade->getDescription(),
            $dataFacade->getPng(),
            $dataFacade->getSrc(),
            $dataFacade->getDst(),
            $dataFacade->getFrameType(),
            $dataFacade->getPduFormat(),
            $dataFacade->getDataPage()
        ). PHP_EOL;
        for ($i = 1; $i <= $dataFacade->count(); $i++) {
            try {
                $result .= sprintf("%s, %s %s, %s %s, %s",
                   $i, $dataFacade->getFieldValue($i)->getName(),
                   $i, $dataFacade->getFieldValue($i)->getValue(),
                   $i, $dataFacade->getFieldValue($i)->getType()
                ).PHP_EOL;
            } catch (ConfigException $e) {
                $result .= $e->getMessage() . PHP_EOL;
            }
        }

        return $result;
    }
}