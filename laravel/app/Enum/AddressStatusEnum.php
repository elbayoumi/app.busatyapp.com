<?php

namespace App\Enum;

enum AddressStatusEnum: int
{
    case NEW = 0;
    case ACCEPT = 1;
    case UNACCEPT = 2;
    case PROCESSING = 3;

    public function getText(): string
    {
        return match($this) {
            self::NEW => __('New'),
            self::ACCEPT => __('Accepted'),
            self::UNACCEPT => __('Refused'),
            self::PROCESSING => __('Pending'),
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::NEW => 'warning',
            self::ACCEPT => 'info',
            self::UNACCEPT => 'danger',
            self::PROCESSING => 'success',
        };
    }
}
