<?php

// app/Enums/SubscriptionStatus.php
namespace App\Enums;

enum SubscriptionStatus: string
{
    case Subscribed = 'subscribed';
    case Coupon = 'coupon';
    case Pending = 'pending';
    case NotSubscribed = 'not_subscribed';
    case Expired = 'expired';
    case Trial = 'trial';
    case Cancel = 'cancel';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
