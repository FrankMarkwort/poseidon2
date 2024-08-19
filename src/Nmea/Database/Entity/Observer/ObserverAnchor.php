<?php

namespace Nmea\Database\Entity\Observer;

class ObserverAnchor implements InterfaceObserver
{
    private const int ANCHOR_ALARM = 10;
    public function update(InterfaceObservable $observable)
    {
        if (! $observable->isAnchorSet()) return;
         echo  sprintf("circle r=%s, dist=%sm, heading=%s°, distInC=%sm, pos(%s, %s) ancPos(%s, %s)",
            $observable->circleRadius(),
            $observable->getMaxDistance(),
            intval(rad2deg($observable->getHeading())),
            $observable->meterInCircle(),
            rad2deg($observable->getLatitude()), rad2deg($observable->getLongitude()),
            rad2deg($observable->getAnchorLatitude()), rad2deg($observable->getAnchorLongitude())
        ) . $this->getStatus($observable);
    }

    protected function getStatus(InterfaceObservable $observable):string
    {
        if ($observable->isAnchorSet()) {
            if ($observable->meterInCircle() <= 0.00) {
                return "\e[32m ANKER OK \e[0m \n";
            } elseif ($observable->meterInCircle() >= static::ANCHOR_ALARM) {
                return "\e[31m ANKER ALARM \e[0m \n";
            } else {
                return "\e[33m ANKER WARN\e[0m \n";
            }
        } else {
            return  "try to setAncor if awa +- 4° (" . round(rad2deg($observable->getAwa()),1) . ")\n";
        }
    }
}

