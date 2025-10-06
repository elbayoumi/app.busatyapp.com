<?php

namespace App\Enums;

enum TripDayStatus:int
{
    case SCHEDULED = 0;
    case OPEN      = 1;
    case CLOSED    = 2;
}
