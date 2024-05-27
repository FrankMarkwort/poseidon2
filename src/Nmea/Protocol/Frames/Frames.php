<?php

namespace Nmea\Protocol\Frames;

use Nmea\Cache\CacheInterface;
use Nmea\Protocol\Frames\Frame\Frame;

class Frames
{
    /*
     * @var Frames[][][]
     */
    private array $frames = [];

    public function __construct(private readonly CacheInterface $cache)
    {
    }

    public function addFrame(Frame $frame):self
    {
        $pgn = $frame->getHeader()->getPgn();
        $sequence = $frame->getSequenceCounter();
        $frameCounter = $frame->getFrameCounter();
        $this->unSetFrameSequenceItIsTheFistFrame($pgn, $sequence, $frameCounter);
        $this->frames[$pgn][$sequence][$frameCounter] = $frame;
        #echo "\$this->frames[$pgn][$sequence][$frameCounter] = " . $this->getFrame($pgn,$sequence,$frameCounter)->getData()->getData() ."\n";
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

    private function addCache(Frame $frame, string $data):void
    {
        $this->cache->set($frame->getHeader()->getPgn(), $frame->getData()->getTimestamp()
                . ' ' . $frame->getData()->getDirection() . ' ' . $frame->getHeader()->getCanIdHex() . ' ' . $data);

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
            };
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
                    };
                } else {
                    foreach ($frame->getData()->getDataBytes() as $index => $byte) {
                        if ($index < 1 ) continue;
                            $data .= $splitter . $byte;
                            $splitter = ' ';
                    };
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