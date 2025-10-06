<?php

namespace App\Enum;

/**
 * Enum representing the status of a student during a trip.
 */
enum TripStudentStatusEnum: string
{
    case WAITING = 'waiting'; // The student is waiting for the bus.
    case ONBOARD = 'onboard'; // The student is on the bus.
    case ARRIVED = 'arrived'; // The student has arrived at the destination.
    case ABSENT = 'absent'; // The student is absent.

    /**
     * Get the human-readable text for each status.
     */
    public function getText(): string
    {
        return match ($this) {
            self::WAITING => __('The student is waiting for the bus.'),
            self::ONBOARD => __('The student is on the bus.'),
            self::ARRIVED => __('The student has arrived at the destination.'),
            self::ABSENT => __('The student is absent.'),
        };
    }

    /**
     * Get the notification type or alert level for each status.
     */
    public function getType(): string
    {
        return match ($this) {
            self::WAITING => 'info',
            self::ONBOARD => 'primary',
            self::ARRIVED => 'success',
            self::ABSENT => 'danger',
        };
    }

    /**
     * Get the color indicator for each status.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::WAITING => 'secondary',
            self::ONBOARD => 'warning',
            self::ARRIVED => 'success',
            self::ABSENT => 'danger',
        };
    }
}
