<?php

namespace App\Enum;

enum TripStatusEnum: int
{
    case COMPLETED = 1;
    case NOT_COMPLETED = 0;


    public function getText(): string
    {
        return match($this) {
            self::COMPLETED => __('The bus trip has been closed.'),
            self::NOT_COMPLETED => __('The bus ride is not over yet.'),
        };
    }
    public function getType(): string
    {
        return match($this) {
            self::COMPLETED => 'success',
            self::NOT_COMPLETED => 'warning',

        };
    }
    public function getColor(): string
    {
        return match($this) {
            self::COMPLETED => 'success',
            self::NOT_COMPLETED => 'warning',

        };
    }

}
