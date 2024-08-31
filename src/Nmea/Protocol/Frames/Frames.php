<?php

namespace Nmea\Protocol\Frames;

use Nmea\Cache\CacheInterface;
use Nmea\Config\ConfigException;
use Nmea\Cron\EnumPgns;
use Nmea\Protocol\Frames\Frame\Frame;
use Nmea\Protocol\Realtime\WindSpeedCourseFactory;
use Nmea\Protocol\Socket\Client;
use Nmea\Parser\ParserException;

class Frames
{
    /*
     * @var Frames[][][]
     */
    private array $frames = [];
    private array $tempStore = [];

    public function __construct(private readonly CacheInterface $cache, private readonly ?Client $webSocket = null)
    {
    }

    public function addFrame(Frame $frame):self
    {
        $pgn = $frame->getHeader()->getPgn();
        $sequence = $frame->getSequenceCounter();
        $frameCounter = $frame->getFrameCounter();
        $this->unSetFrameSequenceItIsTheFistFrame($pgn, $sequence, $frameCounter);
        $this->frames[$pgn][$sequence][$frameCounter] = $frame;
        if ($this->isReady($pgn, $sequence)) {
            if ($this->makeData($this->frames[$pgn][$sequence])) {
                unset($this->frames[$pgn][$sequence]);
                if (count($this->frames[$pgn]) === 0) {
                    unset($this->frames[$pgn]);
                }
            }
        }

        return $this;
    }

    private function unSetFrameSequenceItIsTheFistFrame(int $pgn, int $sequence, int $frameCounter):void
    {
        if ($frameCounter === 0 && isset($this->frames[$pgn][$sequence])) {
            unset($this->frames[$pgn][$sequence]);
        }
    }

    private function isReady(int $pgn, int $sequence):bool
    {
        if (isset($this->frames[$pgn][$sequence][0]) && $this->frames[$pgn][$sequence][0] instanceof Frame) {
            if ($this->getFrame($pgn, $sequence, 0)->numberOfFrames() === $this->countFrames($pgn, $sequence)
                || $this->getFrame($pgn, $sequence, 0)->getHeader()->isSingelPacked()) {

                return true;
            }
        }

        return false;
    }

    public function countFrames(int $pgn, int $sequence):int
    {
        return count($this->frames[$pgn][$sequence]);
    }

    private function getFrame(int $pgn, int $sequence, int $framecounter):Frame
    {
        return $this->frames[$pgn][$sequence][$framecounter];
    }

    private function makeData(array $frames):bool
    {
        if ($frames[0] instanceof Frame) {
            if ($frames[0]->getHeader()->isFastPacked()) {

                return $this->makeFastPackedData($frames);

            } elseif ($frames[0]->getHeader()->isSingelPacked()) {

                return $this->makeSinglePackedData($frames);
            }
        }

        return false;
    }

    /**
     * @throws ConfigException
     */
    private function addCache(Frame $frame, string $data):void
    {
        $this->cache->set($frame->getHeader()->getPgn(), $frame->getData()->getTimestamp()
                . ' ' . $frame->getData()->getDirection() . ' ' . $frame->getHeader()->getCanIdHex() . ' ' . $data);
        try {
            $this->windSocketData(
                $frame->getHeader()->getPgn(),
                $frame->getData()->getTimestamp()
                . ' ' . $frame->getData()->getDirection() . ' ' . $frame->getHeader()->getCanIdHex() . ' ' . $data
            );
        } catch (ParserException $e) {}
    }

    /**
     * @throws ParserException
     * @throws ConfigException
     */
    private function windSocketData($pgn, string $data):void
    {
        if ($this->isNeedForWindData($pgn)) {
            $this->tempStore[$pgn] = $data;
        }
        if ($this->hasAllDataForWindSocket()) {
            $this->writeToSocket();
        }
    }

    /**
     * @throws ConfigException
     * @throws ParserException
     * @throws \ErrorException
     */
    private function writeToSocket():void
    {
        $socketObj = new WindSpeedCourseFactory($this->webSocket);
        $socketObj->writeToSocket(
            $this->tempStore[EnumPgns::WIND->value],
            $this->tempStore[EnumPgns::COG_SOG->value],
            $this->tempStore[EnumPgns::Vessel_Heading->value]
        );
        $this->tempStore = [];
    }

    private function hasAllDataForWindSocket():bool
    {
        if (isset($this->tempStore[EnumPgns::WIND->value])
            && isset($this->tempStore[EnumPgns::Vessel_Heading->value])
            && isset($this->tempStore[EnumPgns::COG_SOG->value])) {

            return true;
        }
        return false;
    }

    private function isNeedForWindData($pgn):bool
    {
        return in_array($pgn, array(EnumPgns::WIND->value,EnumPgns::Vessel_Heading->value,EnumPgns::COG_SOG->value));

    }

    //TODO implement sequence
    private function makeSinglePackedData(array $frames):bool
    {
        if ($frames[0] instanceof Frame) {

            $data = '';
            $splitter = '';
            foreach ($frames[0]->getData()->getDataBytes() as $index => $byte) {
                    $data .= $splitter . $byte ;
                    $splitter = ' ';
            }
            $this->addCache($frames[0], $data);

            return true;
        }

        return false;
    }

    private function makeFastPackedData(array $frames):bool
    {
        $data = '';
        $splitter = '';
        foreach ($frames as $frameCounter => $frame) {
            if ($frame instanceof Frame) {
                if ($frameCounter === 0) {
                    foreach ($frame->getData()->getDataBytes() as $index => $byte) {
                        if ($index < 2 ) continue;
                            $data .= $splitter . $byte;
                            $splitter = ' ';
                    }
                } else {
                    foreach ($frame->getData()->getDataBytes() as $index => $byte) {
                        if ($index < 1 ) continue;
                            $data .= $splitter . $byte;
                            $splitter = ' ';
                    }
                }
            }
        }
        if ($data !== '' && $frames[0] instanceof Frame) {
            $this->addCache($frames[0], $data);

                return true;
        }

        return false;
    }
}