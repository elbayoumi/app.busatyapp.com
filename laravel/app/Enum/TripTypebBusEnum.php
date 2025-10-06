<?php

namespace App\Enum;

enum TripTypebBusEnum: string
{
    case START_DAY = 'start_day';
    case END_DAY = 'end_day';


    public function getText(): string
    {
        return match($this) {
            self::START_DAY => __("Morning bus trip"),
            self::END_DAY => __("Evening bus trip"),
        };
    }

}
