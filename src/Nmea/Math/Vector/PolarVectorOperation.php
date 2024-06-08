<?php

namespace Nmea\Math\Vector;

/**
 *  Minus ist not testet
 */
class PolarVectorOperation
{
    public function __invoke(PolarVector $vector1, PolarVector $vector2, Operator $operator = Operator::PLUS, Range $range = Range::G360): PolarVector
    {
        $omega = $this->getOmega($vector1, $vector2, $operator);
        $r = $this->getR($vector1, $vector2, $operator);

        return (new PolarVector())->setOmega($omega)->setR($r);
    }

    private function getR(PolarVector $vector1, PolarVector $vector2, Operator $operator): float
    {
        $omega1 = $vector1->getOmega();
        $omega2 = $vector2->getOmega();
        $r1 = $vector1->getR();
        $r2 = $vector2->getR();
        $part1 = pow($r1,2) + pow($r2,2);
        $part2 = 2 * abs($r1) * abs($r2) * cos($omega1 - $omega2);
        if ($operator == Operator::PLUS) {
            $z = sqrt($part1 + $part2);
        } elseif ($operator == Operator::MINUS) {
            //TODO sqrt(- )
            $z = sqrt($part1 - $part2);
        }

        return $z;
    }

    private function getOmega(PolarVector $vector1, PolarVector $vector2, Operator $operator): float
    {
        $omega1 = $vector1->getOmega();
        $omega2 = $vector2->getOmega();
        $r1 = $vector1->getR();
        $r2 = $vector2->getR();

        $imPart1 = abs($r1) * sin($omega1);
        $imPart2 = abs($r2) * sin($omega2);

        $rePart1 = abs($r1) * cos($omega1);
        $rePart2 = abs($r2) * cos($omega2);
         if ($operator == Operator::PLUS) {
             $im = $imPart1 + $imPart2;
             $re = $rePart1 + $rePart2;
         } elseif ($operator == Operator::MINUS) {
             $im = $imPart1 - $imPart2;
             $re = $rePart1 - $rePart2;
         }

        return ($im < 0 )? atan2($im , $re) + 2 * pi() : atan2($im , $re);
    }
}