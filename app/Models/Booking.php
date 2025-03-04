<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends BaseModel
{

    // List fields that the model->fill() method can populate
    protected $fillable = [
        'event_id',   // ulid
        'member_id',  // ulid
        'created_by', // ulid
        'state',
        'comments',
    ];


    /**
     * Booking States
     */
    public static $bookingStates = [
        'Provisional', // new booking, not yet confirmed. May be waiting payment.
        'Booked',      // booking confirmed
        'Checked In',  // checked in to the class
        'Cancelled',   // cancelled before the class started; refund may be due
        'Reserve',     // on the waiting list
        'No Show',     // booked but did not check in
        'Deleted',
    ];

    // Helper to access the static states
    public function getBookingStates() : array
    {
        return self::$bookingStates;
    }


    // Default to a Provisional booking
    protected $attributes = [
        'state' => 'Provisional',
    ];


    /*
     * Relationships
     */

    /**
     * Get the member that owns this booking
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    /**
     * Get the event that owns this booking
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }


    /*
     * Query scopes
     */
    /**
     * Find all Bookings that deduct from the available spaces.
     * Excludes Cancelled or Reserve bookings but includes Provisional and No Show bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeFilled(Builder $query) : Builder
    {
        return $query->whereIn('state', ['Provisional', 'Booked', 'Checked In', 'No Show']);
    }


    /**
     * Find All Bookings for display. Excludes Provisional bookings but includes
     * cancelled and no show etc
     * @param Builder $query
     * @return Builder
     */
    public function scopeConfirmed(Builder $query)
    {
        return $query->whereNot('state', 'Provisional');
    }


    /**
     * Find Confirmed Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeBooked(Builder $query)
    {
        return $query->where('state', 'Booked');
    }


    /**
     * Find Checked In Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeCheckedIn(Builder $query)
    {
        return $query->where('state', 'Checked In');
    }


    /**
     * Find Reserve Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeReserves(Builder $query)
    {
        return $query->where('state', 'Reserve');

    }


    /**
     * Find Cancelled Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeCancelled(Builder $query)
    {
        return $query->where('state', 'Cancelled');

    }


    /**
     * Find No Show Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeNoShow(Builder $query)
    {
        return $query->where('state', 'No Show');

    }


    /**
     * Find Provisional Bookings
     * @param Builder $query
     * @return Builder
     */
    public function scopeProvisional(Builder $query)
    {
        return $query->where('state', 'Provisional');

    }



    /*
     * Helper methods for the current model instance
     */

    public function isProvisional() : bool
    {
        return $this->state === 'Provisional';
    }

    public function isExisting() : bool
    {
        return $this->state !== 'Provisional';
    }

    public function isBooked() : bool
    {
        return $this->state === 'Booked';
    }

    public function isReserve() : bool
    {
        return $this->state === 'Reserve';
    }

    public function isCheckedIn() : bool
    {
        return $this->state === 'Checked In';
    }

    public function isNoShow() : bool
    {
        return $this->state === 'No Show';
    }

    public function isCancelled() : bool
    {
        return $this->state === 'Cancelled';
    }


    /*
     * State changes
     */
    /**
     * Is the requested state a permitted change for this booking
     * @param string $requested_state
     * @return bool
     */
    public function isPermittedStateChange(string $requested_state) : bool
    {
        $permit = false;

        if ($requested_state === $this->original['state'])
            return true;  // no change

        switch ($requested_state) {

            case 'Booked':

                switch ($this->original['state']) {

                    // Checked In can always move back to Booked e.g. to fix a mistaken check in
                    case 'Checked In':
                        $permit = true;
                        break;

                    // Reserves and Cancelled can only move to Booked if the class has spaces
                    case 'Provisional':
                    case 'Reserve':
                    case 'Cancelled':
                        $permit = $this->event->hasPlacesAvailable();
                        break;

                }
                break;


            case 'Checked In':

                switch ($this->original['state']) {

                    // Only Booked places can move to Checked In
                    case 'Booked':
                        $permit = true;
                        break;

                }
                break;



            case 'Reserve':

                switch ($this->original['state']) {

                    // Only Provisional, Booked, and Cancelled can move to Reserve
                    case 'Provisional':
                    case 'Booked':
                    case 'Cancelled':
                        $permit = true;
                        break;

                }
                break;


            case 'Cancelled':

                switch ($this->original['state']) {

                    // Only Provisional, Booked and Reserve can be cancelled
                    case 'Provisional':
                    case 'Booked':
                    case 'Reserve':
                        $permit = true;
                        break;

                }
                break;

            case 'Deleted':
                switch ($this->original['state']) {
                    // Only Provisional or Cancelled can be deleted
                    case 'Provisional':
                    case 'Cancelled':
                        $permit = true;
                        break;
                }
                break;


            default:    // requested state unknown or invalid
                $permit = false;

        }

        return $permit;
    }

}
