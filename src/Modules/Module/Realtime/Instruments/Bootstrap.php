<?php

namespace Modules\Module\Realtime\Instruments;

use Modules\Internal\Enums\EnumPgns;
use Modules\Internal\Interfaces\InterfaceObservableRealtime;
use Modules\Internal\Interfaces\InterfaceObserverRealtime;
use Core\Config\ConfigException;
use Core\Parser\ParserException;
use ErrorException;
use Core\Protocol\Socket\SocketException;

class Bootstrap implements InterfaceObserverRealtime
{
    private array $tempStore = [];

    /**
     * @throws ConfigException
     * @throws ErrorException
     * @throws ParserException
     * @throws SocketException
     */
    public function update(InterfaceObservableRealtime $observable): void
    {
        $this->windSocketData(
            $observable,
            $observable->getFrame()->getHeader()->getPgn(),
            sprintf('%s %s %s %s',
                $observable->getFrame()->getData()->getTimestamp(),
                $observable->getFrame()->getData()->getDirection(),
                $observable->getFrame()->getHeader()->getCanIdHex(),
                $observable->getData()
            )
        );
    }

     /**
     * @throws ConfigException
     * @throws ParserException
     * @throws ErrorException
     * @throws SocketException
     */
    private function windSocketData(InterfaceObservableRealtime $observable, int $pgn, string $data):void
    {
        if ($this->isNeedForWindData($pgn)) {
            $this->tempStore[$pgn] = $data;
        }
        if ($this->hasAllDataForWindSocket()) {
            $this->writeToSocket($observable);
        }
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     * @throws ErrorException
     * @throws SocketException
     */
    private function writeToSocket(InterfaceObservableRealtime $observable):void
    {
        $socketObj = new WindSpeedCourseFactory($observable->getWebSocket());
        $socketObj->writeToSocket(
            $this->tempStore[EnumPgns::WIND->value],
            $this->tempStore[EnumPgns::COG_SOG->value],
            $this->tempStore[EnumPgns::VESSEL_HEADING->value]
        );
        $this->reset();
    }

    private function hasAllDataForWindSocket():bool
    {
        if (isset($this->tempStore[EnumPgns::WIND->value])
            && isset($this->tempStore[EnumPgns::VESSEL_HEADING->value])
            && isset($this->tempStore[EnumPgns::COG_SOG->value])) {

            return true;
        }
        return false;
    }

    private function reset():void
    {
        $this->tempStore = [];
    }

    private function isNeedForWindData($pgn):bool
    {
        return in_array($pgn, array(EnumPgns::WIND->value,EnumPgns::VESSEL_HEADING->value,EnumPgns::COG_SOG->value));
    }
}