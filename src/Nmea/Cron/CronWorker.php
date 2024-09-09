<?php
declare(strict_types=1);

namespace Nmea\Cron;

use Exception;
use Modules\Internal\Pgns\EnumPgns;
use Nmea\Config\ConfigException;
use Nmea\Database\Entity\Positions;
use Nmea\Database\Mapper\PositionMapper;
use Nmea\Logger\Factory;
use Nmea\Parser\DataFacadeFactory;
use Nmea\Parser\ParserException;
use TypeError;

final class CronWorker extends AbstractCronWorker
{
    private bool $running = true;
    private ?Positions $position = null;
    private ?PositionMapper $positionMapper = null;
    public function run():void
    {
        $i = 0;
        while ($this->running) {
            $i++;
            try {
                $this->setEveryMinuteRun(true);
                $this->notify();
                if ($i >= 60) {
                    $this->setEveryMinuteRun(false);
                    $i = 0;
                    $this->storePosition(
                        $this->cache->get(EnumPgns::POSITION->value),
                        $this->cache->get(EnumPgns::COG_SOG->value),
                        $this->cache->get(EnumPgns::SET_AND_DRIFT->value)
                    );
                }
                sleep($this->sleepTime - date('s') % $this->sleepTime);
            } catch (ParserException $e) {
                $this->isDebugPrintMessage($e->getMessage().PHP_EOL);
                Factory::log($e->getMessage());
            } catch (ConfigException $f) {
                Factory::log($f->getMessage());
            } catch (Exception $g) {
                Factory::log($g->getMessage());
            } catch (TypeError $typeError) {
                Factory::log('TypeError: '.$typeError->getMessage());
            }
        }
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    private function storePosition(string $position, string $courseOverGround, $drift):void
    {
        if (empty($position) || empty($courseOverGround) || empty($drift)) {
            $this->isDebugPrintMessage('invalid data store position'.PHP_EOL);

            return;
        }
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $courseFacade = DataFacadeFactory::create($courseOverGround, 'YACHT_DEVICE');
        $driftFacade = DataFacadeFactory::create($drift, 'YACHT_DEVICE');
        //$this->isDebugPrintMessage($this->printPositionConsole($positionFacade, $courseFacade, $driftFacade));
        $position = new Positions();
        $position->setLatitude($positionFacade->getFieldValue(1)->getValue())
            ->setLongitude($positionFacade->getFieldValue(2)->getValue())
            ->setCourseOverGround($this->getPolarVector($courseFacade,5,4 ))
            ->setDrift($this->getPolarVector($driftFacade,5,4 ));
        if (! $this->isPositionChanged($position)) {
            $this->getPositionMapper()->storeEntity($position);
            $this->isDebugPrintMessage('store hour position data !' . PHP_EOL);
        }
    }

    private function getPositionMapper():PositionMapper
    {
        if ( ! $this->positionMapper instanceof PositionMapper) {
            $this->positionMapper = new PositionMapper($this->database);
        }

        return $this->positionMapper;
    }

    private function isPositionChanged(Positions $position):bool
    {
        if (! $this->position instanceof Positions) {
            $this->position = $this->getPositionMapper()->fetchLastEntity();

            if (! $this->position instanceof Positions) {

                return false;
            }
        }
        if ($this->position->compareTo($position)) {

            return true;
        }
        $this->position = $position;

        return false;

    }

    public function isDebug():bool
    {
        return false;
    }
}
