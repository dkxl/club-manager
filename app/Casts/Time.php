<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Time implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return Carbon::createFromTimeString($value);
    }

    /**
     * Prepare the given value for storage
     * Convert time strings and Carbon objects to Postgres friendly hours:mins:secs
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {

        if ($value instanceof Carbon) {
            return $value->format('H:i:s');
        }

        return Carbon::createFromTimeString($value)->format('H:i:s');
    }
}
