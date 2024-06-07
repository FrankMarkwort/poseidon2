<?php

namespace Nmea\Database\Mapper\Vector;

enum Range:string
{
    case G180 = 'PI';
    case G360 = '2PI';
}
