<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;


class Venue extends BaseModel
{

    /**
     * Mass assignable attributes
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'capacity'
    ];


    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    /**
     * The events that are using this venue
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }


    /**
     * Return an array of resource names for use in a Selector
     * @return Collection [ $id => $name ]
     */
    public static function selector(): Collection
    {
        return self::all()->pluck('name', 'id');
    }

}
