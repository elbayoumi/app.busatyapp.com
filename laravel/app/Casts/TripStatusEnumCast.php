<?php

namespace App\Casts;

use App\Enum\TripStatusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class TripStatusEnumCast implements CastsAttributes
{
    /**
     * Cast the given value to an enum.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return TripStatusEnum
     */
    public function get($model, string $key, $value, array $attributes): TripStatusEnum
    {
        return TripStatusEnum::from((int) $value); // Ensure correct casting
    }

    /**
     * Prepare the given enum for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return int
     */
    public function set($model, string $key, $value, array $attributes): int
    {
        if ($value instanceof TripStatusEnum) {
            return $value->value;
        }

        Log::info('Incoming value for TripStatusEnum:', ['value' => $value]);

        // Handle incoming string that represents the enum case
        if (is_string($value)) {
            return TripStatusEnum::from($value)->value;
        }

        throw new InvalidArgumentException('The given value is not a valid TripStatusEnum instance.');
    }

}
