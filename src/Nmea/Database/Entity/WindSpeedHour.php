<?php

namespace Nmea\Database\Entity;

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
    private float $avgWatertemperature;
    private float $minWatertemperature;
    private float $maxWatertemperature;
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
        return round($this->avgAwa,0);
    }
    public function setAvgAwa(float $avgAwa): WindSpeedHour
    {
        $this->avgAwa = $avgAwa;
        return $this;
    }
    public function getMaxAwa(): float
    {
        return round($this->maxAwa,0);
    }
    public function setMaxAwa(float $maxAwa): WindSpeedHour
    {
        $this->maxAwa = $maxAwa;
        return $this;
    }
    public function getMinAwa(): float
    {
        return round($this->minAwa,0);
    }
    public function setMinAwa(float $minAwa): WindSpeedHour
    {
        $this->minAwa = $minAwa;
        return $this;
    }
    public function getAvgTws(): float
    {
        return round($this->avgTws,1);
    }
    public function setAvgTws(float $avgTws): WindSpeedHour
    {
        $this->avgTws = $avgTws;
        return $this;
    }
    public function getMaxTws(): float
    {
        return round($this->maxTws,1);
    }
    public function setMaxTws(float $maxTws): WindSpeedHour
    {
        $this->maxTws = $maxTws;
        return $this;
    }
    public function getMinTws(): float
    {
        return round($this->minTws,1);
    }
    public function setMinTws(float $minTws): WindSpeedHour
    {
        $this->minTws = $minTws;
        return $this;
    }
    public function getAvgTwa(): float
    {
        return round($this->avgTwa,0);
    }
    public function setAvgTwa(float $avgTwa): WindSpeedHour
    {
        $this->avgTwa = $avgTwa;
        return $this;
    }
    public function getMaxTwa(): float
    {
        return round($this->maxTwa,0);
    }
    public function setMaxTwa(float $maxTwa): WindSpeedHour
    {
        $this->maxTwa = $maxTwa;
        return $this;
    }
    public function getMinTwa(): float
    {
        return round($this->minTwa,1);
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
        return round($this->avgTwd,0);
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

    public function getAvgWatertemperature(): float
    {
        return $this->avgWatertemperature;
    }

    public function setAvgWatertemperature(float $avgWatertemperature): WindSpeedHour
    {
        $this->avgWatertemperature = $avgWatertemperature;

        return $this;
    }

    public function getMinWatertemperature(): float
    {
        return $this->minWatertemperature;
    }

    public function setMinWatertemperature(float $minWatertemperature): WindSpeedHour
    {
        $this->minWatertemperature = $minWatertemperature;

        return $this;
    }

    public function getMaxWatertemperature(): float
    {
        return $this->maxWatertemperature;
    }

    public function setMaxWatertemperature(float $maxWatertemperature): WindSpeedHour
    {
        $this->maxWatertemperature = $maxWatertemperature;

        return $this;
    }

}