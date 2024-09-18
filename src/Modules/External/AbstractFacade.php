<?php

namespace Modules\External;

use Math\Skalar\Rad;
use Math\Vector\PolarVector;

abstract class AbstractFacade
{
    protected function getPolarVector(float $rFieldValue, float $omegaFieldvalue): PolarVector
    {
         return (new PolarVector())
             ->setR($rFieldValue)
             ->setOmega($omegaFieldvalue);
    }

    protected function getRad(float $rad):Rad
    {
        return (new Rad())->setOmega($rad);
    }
}