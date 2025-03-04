<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DateUk implements CastsAttributes
{

    /**
     * Cast the given value.
     * Display in UK format
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return '';      // else Carbon::create() will default to 'now'
        }

        return Carbon::create($value)->format('d-m-Y');
    }

    /**
     * Prepare the given value for storage
     * Store in ISO format
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->format('Y-m-d');
        }

        return Carbon::create($value)->format('Y-m-d');
    }
}
