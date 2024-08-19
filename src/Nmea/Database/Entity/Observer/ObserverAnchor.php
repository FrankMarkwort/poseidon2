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
            intval(rad2deg($observable->getHeadingRad())),
            $observable->meterInCircle(),
            rad2deg($observable->getLatitudeRad()), rad2deg($observable->getLongitudeRad()),
            rad2deg($observable->getAnchorLatitudeRad()), rad2deg($observable->getAnchorLongitudeRad())
        ) . $this->getStatus($observable);
    }

    protected function getStatus(InterfaceObservable $observable):string
    {

        if ($observable->isAnchorSet()) {
            if ($observable->hasAlarm() && $observable->hasWarn()) {

                return "\e[31m ANKER ALARM \e[0m \n";

            } elseif ($observable->hasWarn()) {

                return "\e[33m ANKER WARN\e[0m \n";
            }

            return "\e[32m ANKER OK \e[0m \n";
        }

        return  "try to setAncor if awa +- 4° (" . round(rad2deg($observable->getAwaDeg360()),1) . ")\n";
    }
}

