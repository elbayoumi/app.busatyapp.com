<?php

namespace App\Casts;

use App\Enum\TripTypebBusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class TripTypeEnumCast implements CastsAttributes
{
    /**
     * Cast the given value to an enum.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return TripTypeEnum
     */
    public function get($model, string $key, $value, array $attributes): TripTypebBusEnum
    {
        return TripTypebBusEnum::from((string) $value);
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
    public function set($model, string $key, $value, array $attributes): string
    {
        if ($value instanceof TripTypebBusEnum) {
            return $value->value;
        }

        throw new InvalidArgumentException('The given value is not a valid TripTypeEnum instance.');
    }
}
