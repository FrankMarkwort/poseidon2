<?php

namespace Modules\AnchorWatch;

class Bootstrap
{
/**
     * @throws ParserException
     * @throws ConfigException
     */
    private function anchor(string $position, string $vesselHeading, string $waterDepth, string $windData): void
    {
        $positionFacade = DataFacadeFactory::create($position, 'YACHT_DEVICE');
        $vesselHeadingFacade = DataFacadeFactory::create($vesselHeading, 'YACHT_DEVICE');
        $waterDepthFacade = DataFacadeFactory::create($waterDepth, 'YACHT_DEVICE');
        $windFacade = DataFacadeFactory::create($windData, 'YACHT_DEVICE');
        $this->anchor->setPosition(
            $positionFacade->getFieldValue(1)->getValue(),
            $positionFacade->getFieldValue(2)->getValue(),
            $vesselHeadingFacade->getFieldValue(2)->getValue(),
            ($waterDepthFacade->getFieldValue(2)->getValue() + $waterDepthFacade->getFieldValue(3)->getValue()),
            $windFacade->getFieldValue(3)->getValue(),
            $windFacade->getFieldValue(2)->getValue()
        );
        if (! $this->cache->isSet(CronWorker::CHAIN_LENGTH)) {
            $this->anchor->unsetAnchor();
            $this->cache->delete('OBJ_ANCHOR');
        } else {
            if (! $this->anchor->isAnchorSet()) {
                $this->anchor->setAnchor($this->cache->get(CronWorker::CHAIN_LENGTH));
            }
        }
    }
}