<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends BaseModel
{

    protected $casts = [
        'metadata' => 'array',
        'edited_child' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
        'name',
        'description',
        'starts_at',
        'ends_at',
        'venue_id',   // ulid
        'instructor_id',   // ulid
        'metadata', // json array[css, capacity, payment, report_ref, canx, ...]
        'series_id', // for repeating events
        'edited_child', // true if a child event has been individually edited
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


    /**
     * The bookings for this event
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    /**
     * The parent for a series child
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(EventSeries::class)->withDefault();
    }


    /**
     * Is this the Series definition record?
     * @return bool
     */
    public function isSeriesParent()
    {
        return false;
    }


    /**
     * Appointments for this day
     *
     * @param Builder $query
     * @param Carbon $the_date
     */
    public function scopeForDay(Builder $query, Carbon $the_date)
    {

        // startOfDay() and endOfDay() both alter the same $the_date Carbon object, so calling $the_date->startOfDay()
        // overwrites the effect of $the_date->endOfDay(). Cast to string, or copy $the_date object to avoid this

        return $query->where('starts_at', '<', $the_date->endOfDay()->toISOString())
                     ->where('ends_at', '>=', $the_date->startOfDay()->toISOString());
    }


    /**
     * Appointments for Monday to Sunday of the week containing $the_date
     * @param Builder $query
     * @param Carbon $the_date
     */
    public function scopeForWeek(Builder $query, Carbon $the_date)
    {
        return $query->where('starts_at', '<', $the_date->startOfWeek()->toISOString())
                     ->where('ends_at', '<=', $the_date->endOfWeek()->toISOString());
    }


    /**
     * @param Builder $query
     * @param string $venue_id
     * @return Builder
     */
    public function scopeForVenue(Builder $query, string $venue_id) {
        return $query->where('venue_id', $venue_id);
    }


    /**
     * @param Builder $query
     * @param string $instructor_id
     * @return Builder
     */
    public function scopeForInstructor(Builder $query, string $instructor_id) {
        return $query->where('instructor_id', $instructor_id);
    }


    /**
     * Events which overlap this timeslot
     * @param Builder $query
     * @param string|Carbon $starts_at
     * @param string|Carbon $ends_at
     * @return Builder
     */
    public function scopeOverlaps(Builder $query, string|Carbon $starts_at, string|Carbon $ends_at)
    {
        // PDO safety - prevent SQL injections
        if ($starts_at instanceof Carbon) {
            $starts_at = $starts_at->toISOString();
        } else {
            $starts_at = Carbon::parse($starts_at)->toISOString();
        }

        if ($ends_at instanceof Carbon) {
            $ends_at = $ends_at->toISOString();
        } else {
            $ends_at = Carbon::parse($ends_at)->toISOString();
        }

        $q = "((starts_at >= '$starts_at' AND starts_at < '$ends_at') OR (ends_at > '$starts_at' AND ends_at <= '$ends_at'))";

        return $query->whereRaw($q);

    }

    /**
     * Find events that conflict/overlap other bookings for this venue
     * @param Builder $query
     * @param string|Carbon $starts_at
     * @param string|Carbon $ends_at
     * @param string $venue_id
     * @return Builder
     */
    public function scopeVenueConflicts(Builder $query, string|Carbon $starts_at, string|Carbon $ends_at,
                                        string $venue_id)
    {
        return $query->where('venue_id', $venue_id)->overlaps($starts_at, $ends_at);
    }

    /**
     * Find events that conflict/overlap other bookings for this instructor
     * @param Builder $query
     * @param string|Carbon $starts_at
     * @param string|Carbon $ends_at
     * @param string $instructor_id
     * @return Builder
     */
    public function scopeInstructorConflicts(Builder $query, string|Carbon $starts_at, string|Carbon $ends_at,
                                             string $instructor_id)
    {
        return $query->where('instructor_id', $instructor_id)->overlaps($starts_at, $ends_at);
    }


    /**
     * Find events that conflict/overlap other bookings for this venue or this instructor
     * @param Builder $query
     * @param string|Carbon $starts_at
     * @param string|Carbon $ends_at
     * @param string $venue_id
     * @param string $instructor_id
     * @return mixed
     */
    public function scopeResourceConflicts(Builder $query, string|Carbon $starts_at, string|Carbon $ends_at,
                                           string $venue_id, string $instructor_id)
    {
        return $query->whereRaw("(venue_id = '$venue_id' OR instructor_id = '$instructor_id')")
                     ->overlaps($starts_at, $ends_at);
    }


    /**
     * Exclude this event id from query results
     * @param Builder $query
     * @param string|null $event_id
     * @return Builder
     */
    public function scopeExcludeEventId(Builder $query, string|null $event_id)
    {
        if (empty($event_id)) {
            return $query;
        }
        return $query->whereNot('id', $event_id);
    }


    /**
     * Exclude this series id from query results
     * @param Builder $query
     * @param string|null $series_id
     * @return Builder
     */
    public function scopeExcludeSeriesId(Builder $query, string|null $series_id)
    {
        if (empty($series_id)) {
            return $query;
        }
        return $query->whereNot('series_id', $series_id);
    }


    /*
     * Event booking helpers
     */

    /**
     * Does this member have any Existing bookings on the event?
     * @param Member $member
     * @return bool
     */
    public function isAlreadyBooked(string $member_id) : bool
    {
        return $this->bookings()->where('member_id', $member_id)->exists();
    }


    /**
     * Is the event Closed for new bookings?
     *  - Cancelled, already started, or too far in the future
     * @return boolean
     */
    public function isClosed() : bool
    {
        return ( $this->isCancelled() || $this->isFinished() || $this->isNotYetOpen() );
    }


    public function isOpen() : bool
    {
        return ! $this->isClosed();
    }

    /**
     * Has the event finished, and past the grace period for logging extra attendees?
     * @return boolean
     */
    public function isFinished() : bool
    {
        // use copy() so we do not modify $this->ends_at
        $closes_at = $this->ends_at->copy()->addHours(config('club.closes-hours'));
        return ( Carbon::now()->gt($closes_at) );
    }


    /**
     * Is the event too far in the future to accept new bookings?
     * @return boolean
     */
    public function isNotYetOpen() : bool
    {
        $opens_at = $this->starts_at->copy()->subDays(config('club.prebook-days'));
        return  ( Carbon::now()->lt($opens_at) );
    }


    /**
     * Has the event been marked as cancelled?
     * @return bool
     */
    public function isCancelled() : bool
    {
        return  ( isset($this->metadata['canx']) && $this->metadata['canx'] );
    }

    /**
     * Reporting reference.
     * Useful for aggregating booking data across multiple series of events
     * E.g. report attendance for all Pilates classes
     * @return string
     */
    public function getReference() : string
    {
        return isset($this->metadata['report_ref']) ? $this->metadata['report_ref'] : '';
    }

    /**
     * Capacity of the event. Just an alias for metadata['capacity']
     * @return int
     */
    public function countCapacity() : int
    {
        return (int) isset($this->metadata['capacity']) ? $this->metadata['capacity'] : 0;
    }


    /**
     * Number of filled places
     * @return int
     */
    public function countFilled() : int
    {
        return $this->bookings()->filled()->count();
    }


    /**
     * Number of remaining places
     * @return int
     */
    public function countRemaining() : int
    {
        return $this->countCapacity() - $this->countFilled();
    }


    /**
     * Number of reserves
     * @return int
     */
    public function countReserves() : int
    {
        return $this->bookings()->reserves()->count();
    }


    /**
     * Are places available?
     * @return boolean
     */
    public function hasPlacesAvailable() : bool
    {

        // no capacity for cancelled classes
        if ($this->isCancelled())
            return false;

        return (bool) $this->countRemaining();

    }

    /**
     * Is the event full?
     * @return bool
     */
    public function isFull() : bool
    {
        return ! $this->hasPlacesAvailable();
    }

    /**
     * Are there any bookings for this event?
     * @return bool
     */
    public function hasBookings() : bool
    {
        return $this->bookings()->exists();
    }



}
