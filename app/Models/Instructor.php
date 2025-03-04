<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Instructor extends BaseModel
{

    /**
     * Mass assignable attributes
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'skills',
        'available',
    ];


    protected $casts = [
        'available' => 'boolean',
    ];


    /**
     * The events that are using this instructor
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
