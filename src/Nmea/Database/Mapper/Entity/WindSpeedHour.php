<?php

namespace Nmea\Database\Mapper\Entity;

use \DateTime;
class WindSpeedHour
{
    private float $avgTwd;
    private float $maxTwd;
    private float $minTwd;
    private float $avgAws;
    private float $maxAws;
    private float $minAws;
    private float $avgAwa;
    private float $maxAwa;
    private float $minAwa;
    private float $avgTws;
    private float $maxTws;
    private float $minTws;
    private float $avgTwa;
    private float $maxTwa;
    private float $minTwa;
    private float $avgCog;
    private float $maxCog;
    private float $minCog;
    private float $avgSog;
    private float $maxSog;
    private float $minSog;
    private float $avgVesselHeading;
    private float $maxVesselHeading;
    private float $minVesselHeading;
    private string $date;

    public function getMaxTwd(): float
    {
        return $this->maxTwd;
    }
    public function setMaxTwd(float $maxTwd): WindSpeedHour
    {
        $this->maxTwd = $maxTwd;
        return $this;
    }
    public function getMinTwd(): float
    {
        return $this->minTwd;
    }
    public function setMinTwd(float $minTwd): WindSpeedHour
    {
        $this->minTwd = $minTwd;
        return $this;
    }
    public function getAvgAws(): float
    {
        return round($this->avgAws,1);
    }
    public function setAvgAws(float $avgAws): WindSpeedHour
    {
        $this->avgAws = $avgAws;
        return $this;
    }
    public function getMaxAws(): float
    {
        return round($this->maxAws,1);
    }
    public function setMaxAws(float $maxAws): WindSpeedHour
    {
        $this->maxAws = $maxAws;
        return $this;
    }
    public function getMinAws(): float
    {
        return round($this->minAws,1);
    }
    public function setMinAws(float $minAws): WindSpeedHour
    {
        $this->minAws = $minAws;
        return $this;
    }
    public function getAvgAwa(): float
    {
        return $this->avgAwa;
    }
    public function setAvgAwa(float $avgAwa): WindSpeedHour
    {
        $this->avgAwa = $avgAwa;
        return $this;
    }
    public function getMaxAwa(): float
    {
        return $this->maxAwa;
    }
    public function setMaxAwa(float $maxAwa): WindSpeedHour
    {
        $this->maxAwa = $maxAwa;
        return $this;
    }
    public function getMinAwa(): float
    {
        return $this->minAwa;
    }
    public function setMinAwa(float $minAwa): WindSpeedHour
    {
        $this->minAwa = $minAwa;
        return $this;
    }
    public function getAvgTws(): float
    {
        return $this->avgTws;
    }
    public function setAvgTws(float $avgTws): WindSpeedHour
    {
        $this->avgTws = $avgTws;
        return $this;
    }
    public function getMaxTws(): float
    {
        return $this->maxTws;
    }
    public function setMaxTws(float $maxTws): WindSpeedHour
    {
        $this->maxTws = $maxTws;
        return $this;
    }
    public function getMinTws(): float
    {
        return $this->minTws;
    }
    public function setMinTws(float $minTws): WindSpeedHour
    {
        $this->minTws = $minTws;
        return $this;
    }
    public function getAvgTwa(): float
    {
        return $this->avgTwa;
    }
    public function setAvgTwa(float $avgTwa): WindSpeedHour
    {
        $this->avgTwa = $avgTwa;
        return $this;
    }
    public function getMaxTwa(): float
    {
        return $this->maxTwa;
    }
    public function setMaxTwa(float $maxTwa): WindSpeedHour
    {
        $this->maxTwa = $maxTwa;
        return $this;
    }
    public function getMinTwa(): float
    {
        return $this->minTwa;
    }
    public function setMinTwa(float $minTwa): WindSpeedHour
    {
        $this->minTwa = $minTwa;
        return $this;
    }
    public function getAvgCog(): float
    {
        return $this->avgCog;
    }
    public function setAvgCog(float $avgCog): WindSpeedHour
    {
        $this->avgCog = $avgCog;
        return $this;
    }
    public function getMaxCog(): float
    {
        return $this->maxCog;
    }
    public function setMaxCog(float $maxCog): WindSpeedHour
    {
        $this->maxCog = $maxCog;
        return $this;
    }
    public function getMinCog(): float
    {
        return $this->minCog;
    }
    public function setMinCog(float $minCog): WindSpeedHour
    {
        $this->minCog = $minCog;
        return $this;
    }
    public function getAvgSog(): float
    {
        return $this->avgSog;
    }
    public function setAvgSog(float $avgSog): WindSpeedHour
    {
        $this->avgSog = $avgSog;
        return $this;
    }
    public function getMaxSog(): float
    {
        return $this->maxSog;
    }
    public function setMaxSog(float $maxSog): WindSpeedHour
    {
        $this->maxSog = $maxSog;
        return $this;
    }
    public function getMinSog(): float
    {
        return $this->minSog;
    }
    public function setMinSog(float $minSog): WindSpeedHour
    {
        $this->minSog = $minSog;
        return $this;
    }
    public function getAvgVesselHeading(): float
    {
        return $this->avgVesselHeading;
    }
    public function setAvgVesselHeading(float $avgVesselHeading): WindSpeedHour
    {
        $this->avgVesselHeading = $avgVesselHeading;
        return $this;
    }
    public function getMaxVesselHeading(): float
    {
        return $this->maxVesselHeading;
    }
    public function setMaxVesselHeading(float $maxVesselHeading): WindSpeedHour
    {
        $this->maxVesselHeading = $maxVesselHeading;
        return $this;
    }
    public function getMinVesselHeading(): float
    {
        return $this->minVesselHeading;
    }
    public function setMinVesselHeading(float $minVesselHeading): WindSpeedHour
    {
        $this->minVesselHeading = $minVesselHeading;
        return $this;
    }
    public function getDate(): string
    {
        return $this->date;
    }

    public function getAvgTwd(): float
    {
        return $this->avgTwd;
    }

    public function setAvgTwd(float $avgTwd): WindSpeedHour
    {
        $this->avgTwd = $avgTwd;
        return $this;
    }

    public function setDate(string $date): WindSpeedHour
    {
        $this->date = $date;
        return $this;
    }
}