<?php

namespace App\Models;

class Note extends BaseModel
{

    protected $table = 'notes';

    public $incrementing = false; // primary key is not an integer sequence


    // data maps
    public $alerts = [
        0 => 'None',
        1 => 'Active',
        2 => 'Complete',
    ];

    public $topics = [
        0 => 'None',
        1 => 'General',
        2 => 'Classes',
        3 => 'Members',
        4 => 'Training',
        5 => 'CheckIns',
        6 => 'Fitvibe',
        7 => 'Retention',
        8 => 'Contracts',
        9 => 'Payments',
        10 => 'Prospects',
        11 => 'Appointments',
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
        'member_id', //ulid
        'created_by', //ulid
        'topic',   // integer
        'note',  // text
        'alert', // integer
    ];

    protected $casts = [
        'topic' => 'integer',
        'alert' => 'integer',
    ];


    /*
     * Relationships.
     */

    /**
     * Get the member that owns this note
     */
    public function member() {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
