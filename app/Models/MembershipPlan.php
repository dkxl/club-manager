<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Casts\Time;
use App\Casts\Currency;


class MembershipPlan extends BaseModel
{

    /*
     * By default, all data is returned as a string. We can override if need be
     */
    protected $casts = [
        'free_classes' => 'boolean',
        'available' => 'boolean',
        'jf_amount' => Currency::class,
        'puf_amount' => Currency::class,
        'dd_amount' => Currency::class,
        'term_months' => 'integer',
        'start_time' => Time::class,
        'end_time' => Time::class,
    ];



    // List fields that the model->fill() method can populate
    protected $fillable = [
            'name',
            'free_classes',
            'available',
            'jf_amount',
            'puf_amount',
            'dd_amount',
            'term_months',
            'start_time',
            'end_time',
    ];


    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'free_classes' => false,
        'available' => true,
        'jf_amount' => 0.00,
        'puf_amount' => 0.00,
        'dd_amount' => 0.00,
        'term_months' => 12,
        'start_time' => '06:00',
        'end_time' => '22:00'
    ];


    /*
     * Relationships
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }


}
