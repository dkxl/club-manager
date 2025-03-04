<?php

namespace App\Models;

use App\Casts\DateUk;
use App\Services\DiaryServices;


use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class EventSeries extends BaseModel
{

    public $incrementing = false; // Not using an integer as the primary key

    // Casts
    protected $casts = [
        'metadata' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'repeat_until' => 'datetime',
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
        'name',
        'description',
        'starts_at',  // datetime
        'ends_at',  // datetime
        'repeat_until',  // date
        'repeat_type',   // string
        'venue_id',   // ulid
        'instructor_id',  //ulid
        'metadata', // json array[css, capacity, payment, report_ref, canx, ...]
    ];


    public $repeatTypes = [
        'None',
        'Daily',
        'Weekly',
        'Monthly',
        'Yearly',
    ];

    public $cssStyles = [
        'diary-magenta',
        'diary-green',
        'diary-blue',
        'diary-yellow',
        'diary-red',
    ];


    /**
     * Get the instructor for this event
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }


    /**
     * Get the venue for this event
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }


    public function children(): HasMany
    {
        return $this->hasMany(Event::class, 'series_id', 'id');
    }


    /**
     * Is this the Series definition record?
     * @return bool
     */
    public function isSeriesParent()
    {
        return (bool) $this->id;  // only true if the record has been saved
    }


}
