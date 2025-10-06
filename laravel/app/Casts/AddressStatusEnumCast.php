<?php

namespace App\Casts;

use App\Enum\AddressStatusEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class AddressStatusEnumCast implements CastsAttributes
{
    /**
     * Cast the given value to an enum.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return AddressStatusEnum
     */
    public function get($model, string $key, $value, array $attributes): AddressStatusEnum
    {
        return AddressStatusEnum::from((int) $value);
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
        if ($value instanceof AddressStatusEnum) {
            return $value->value;
        }

        throw new InvalidArgumentException('The given value is not a valid AddressStatusEnum instance.');
    }
}
