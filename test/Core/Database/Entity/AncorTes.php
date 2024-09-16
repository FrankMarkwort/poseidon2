<?php

namespace TestCore\Database\Entity;

use Modules\Module\Cron\AnchorWatch\Anchor;
use PHPUnit\Framework\TestCase;

class AncorTes extends TestCase
{

    protected function testGetWhaterDeep()
    {
        $ancor = new Anchor();
        $ancor->setPosition(37.1292058,26.8534125, 225, 5, 5, 5)->setAnchor(40);
      #  $this->assertTrue($ancor->isInCircle());

       # $ancor->setPosition(37.1292058,26.8534125, 225, 5);
       # $this->assertTrue($ancor->isInCircle());


    }
}
