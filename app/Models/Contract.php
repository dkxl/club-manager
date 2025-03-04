<?php

namespace App\Models;

use App\Casts\Currency;
use App\Casts\DateUk;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends BaseModel
{

    public $contractStates = [
        0 => 'New',
        1 => 'OK',
        2 => 'Frozen',
        3 => 'Overdue',
        4 => 'Cancelled',
    ];

    // Defaults for a new contract
    protected $attributes = [
        'state' => 0,
    ];


    /*
     * Tell the model not to retrieve these as strings
     */
    protected $casts = [
        'state' => 'integer',
        'start_date' => DateUk::class,
        'end_date' => DateUk::class,
        'dd_first' => DateUk::class,
        'dd_last' => DateUk::class,
        'canx_date' => DateUk::class,
        'jf_amount' => Currency::class,
        'puf_amount' => Currency::class,
        'dd_amount' => Currency::class,
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
            'plan_id',  // ulid
            'member_id', // ulid
            'state',
            'start_date',
            'end_date',
            'jf_amount',
            'puf_amount',
            'dd_amount',
            'dd_day',
            'dd_first',
            'dd_last',
            'canx_date'
    ];



    /*
     * Relationships
     */

    /**
     * Get the member that owns this contract
     */
    public function member() {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    /**
     * Link to the membership plan details
     */
    public function membership_plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id', 'id');
    }


    /*
     * Scopes
     */

    /**
     * Contracts for this member
     * @param Builder $query
     * @param Member $member
     */
    public function scopeForMember(Builder $query, Member $member)
    {
        return $query->where('member_id', $member->id);
    }


    /**
     * Contracts that are currently active
     * Between start_date and end_date, and not cancelled
     * @param Builder $query
     */
    public function scopeWhereActive(Builder $query)
    {
        $now = Carbon::now()->format('Y-m-d');

        return $query->where('start_date', '<=', $now)
            ->whereRaw('COALESCE(end_date, current_date) >= ?', $now)  // using coalesce in case end_date is null
            ->whereRaw('COALESCE(canx_date, current_date) >= ?', $now);
    }


    /**
     * Return the current active contract
     * If more than one contract is active, returns the contract with the most recent start_date
     * @param Builder $query
     * @returns Contract|null
     */
    public function scopeCurrentContract(Builder $query)
    {
        return $query->whereActive()
            ->orderBy('start_date', 'desc')
            ->limit(1);
    }


    /**
     * Is now a permitted entry time?
     * @return bool
     */
    public function isPermittedEntryTime()
    {
        $time = Carbon::now();

        $plan = $this->membership_plan;

        return $plan->start_time <= $time
                && $time <= $plan->end_time;

    }



    /*
     * View Helpers
     */
    /**
     * Return the text description of the current contract state code
     * @return string
     */
    public function stateString(): string {
        return is_null($this->state) ? 'None' : $this->contractStates[$this->state];
    }





}
