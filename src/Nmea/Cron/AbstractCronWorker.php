<?php
declare(strict_types=1);

namespace Nmea\Cron;

use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Database\DatabaseInterface;
use Nmea\Math\Skalar\Rad;
use Nmea\Math\Vector\PolarVector;
use Nmea\Parser\Data\DataFacade;

abstract class AbstractCronWorker
{
    abstract function run():void;

     public function __construct(
        protected readonly int $sleepTime,
        protected readonly DatabaseInterface $database,
        protected readonly CacheInterface $cache,
        protected readonly ModeEnum $runMode = ModeEnum::NORMAL
    ) {}

    public function isDebugRunMode(): bool
    {
        return $this->runMode === ModeEnum::DEBUG || $this->runMode === ModeEnum::NORMAL_PLUS_DEBUG;
    }

    /**
     * @throws ConfigException
     */
    protected function printAllFieldNames(DataFacade $dataFacade):string
    {
        $result = $dataFacade->getDescription() . ' pgn => ' . $dataFacade->getPng() . " src => " .$dataFacade->getSrc() . ' dst => ' . $dataFacade->getDst()
            . ' type => ' .  $dataFacade->getFrameType(). ' pduFormat => ' .$dataFacade->getPduFormat() . ' dataPage => ' . $dataFacade->getDataPage() . PHP_EOL;
        for ($i = 1; $i <= $dataFacade->count(); $i++) {
            try {
                $result .= "$i, " . $dataFacade->getFieldValue($i)->getName() . " "
                    . "$i, " . $dataFacade->getFieldValue($i)->getValue() . " "
                    . "$i, " . $dataFacade->getFieldValue($i)->getType() . PHP_EOL;
            } catch (ConfigException $e) {
                $result .= $e->getMessage() . PHP_EOL;
            }
        }

        return $result;
    }

    protected function isDebugPrintMessage(string $message):bool
    {
         if (in_array($this->runMode, [ModeEnum::DEBUG, ModeEnum::NORMAL_PLUS_DEBUG])) {
                echo $message . PHP_EOL;

                return true;
         }

         return false;
    }

    /**
     * @throws ConfigException
     */
     protected function getPolarVector(DataFacade $dataFacade, int $rFieldValue, int $omegaFieldvalue): PolarVector
    {
         return (new PolarVector())
             ->setR($dataFacade->getFieldValue($rFieldValue)->getValue())
             ->setOmega($dataFacade->getFieldValue($omegaFieldvalue)->getValue()
         );
    }

    protected function getNewRad(float $rad):Rad
    {
        return (new Rad())->setOmega($rad);
    }
}