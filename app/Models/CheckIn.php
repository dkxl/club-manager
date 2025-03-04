<?php

namespace App\Models;


use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;


class CheckIn extends BaseModel
{

    /*
     * Tell the model not to retrieve these as strings
     */
    protected $casts = [
        'permitted' => 'boolean',
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
            'member_id', // ulid
            'card_number',
            'permitted',
            'reason',
    ];

    // Default to Check In denied
    protected $attributes = [
        'permitted' => false,
        'reason' => 'Incomplete',
    ];


    /*
     * Relationships
     */

    /**
     * The Member for this visit
     */
    public function member() {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }


    /*
     * Scopes
     */

    /**
     * Check Ins for this member
     * @param Builder $query
     * @param Member $member
     */
    public function scopeForMember(Builder $query, Member $member)
    {
        return $query->where('member_id', $member->id);
    }


    /**
     * Check Ins where entry was permitted
     * @param Builder $query
     */
    public function scopePermitted(Builder $query)
    {
        return $query->where('permitted', true);
    }


    /**
     * Check Ins where entry was denied
     * @param Builder $query
     */
    public function scopeDenied(Builder $query)
    {
        return $query->whereNot('permitted', false);
    }


    /**
     * Check Ins for this day
     * @param Builder $query
     * @param Carbon|null $the_date - default today()
     */
    public function scopeForDay(Builder $query, Carbon $the_date=null)
    {

        if (is_null($the_date)) {
            $the_date = Carbon::today();
        }

        return $query
            ->whereRaw('DATE(created_at) = ?', [$the_date::format('Y-m-d')])
            ->orderBy('created_at', 'desc');

    }

    /**
     * Find all visits within the last $minutes
     * @param Builder $query
     * @param int $minutes
     */
    public function scopeForLastMinutes(Builder $query, int $minutes=30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subMinutes($minutes));
    }

    /**
     * The most recent visit only
     * @param Builder $query
     */
    public function scopeLastVisit(Builder $query)
    {
        return $query->orderBy('id', 'desc')->first();
    }

    /**
     * All visits for the current month (includes failed attempts)
     * @param Builder $builder
     */
    public function scopeThisMonth(Builder $query)
    {
        return $query->whereRaw('MONTH(created_at) = ?', [Carbon::now()->month]);
    }


    /**
     * All visits for the previous month (includes failed attempts)
     * @param Builder $builder
     */
    public function scopeLastMonth(Builder $query)
    {
        return $query->whereRaw('MONTH(created_at) = ?', [Carbon::now()->subMonth()->month]);
    }



    /**
     * Any recent visits for this member within the checkin_debounce interval?
     * @param Member $member
     * @return bool
     */
    public function isDoubleVisit(Member $member) : bool
    {
        return (bool) self::forMember($member)
                             ->forLastMinutes(config('club.double_visit_minutes'))
                             ->where('permitted', true)
                             ->count();
    }


    public function handleCheckInRequest() {

        // Find the member by id or card_number
        if ($this->member_id) {
            $member = Member::find($this->member_id);
        } else {
            $member = Member::where('card_number', $this->card_number)->first();
            if ($member) {
                $this->member_id = $member->id;
            }
        }

        abort_if(empty($member), 404, 'Member not found');

        // Look for an active membership contract
        $currentContract = $member->contracts()->currentContract()->first();

        if (!$currentContract) {
            $this->permitted = false;
            $this->reason = 'Non-Member';

        } elseif ($currentContract->state > 1) {  // not 'New' or 'OK'
            $this->permitted = false;
            $this->reason = $currentContract->stateString(); // store the string form of the contract state

        } elseif (!$currentContract->isPermittedEntryTime()) {
            $this->permitted = false;
            $this->reason = 'Outside permitted times';

        } else {
            $this->permitted = true;
            $this->reason = 'OK';
        }

        // De-bounce check in attempts
        if ($this->permitted && $this->isDoubleVisit($member)){
            $this->permitted = false;
            $this->reason = 'Already Checked In';
        }

    }


}
