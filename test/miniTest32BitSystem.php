<?php
require_once __DIR__ . '/../vendor/autoload.php';

use \Core\Parser\Lib\BinDec;

if ( 37.1293901 === BinDec::bin2dec32BitSystem('0000010100100111000110011101010111110100010111111100001000000000', true, 1E-16)) {
    echo "itsWorking\n";
}