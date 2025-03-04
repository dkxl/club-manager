<?php

namespace App\Models;


use App\Models\todo\DkxlBaseModel;

class ClassCredit extends DkxlBaseModel
{

    protected $table = 'class_credits';


    // List fields that the model->fill() method can populate
    protected $fillable = [
        'member_id',
        'event_name',
        'aggregate_id',
        'credits',
        'debits',
        'balance',
    ];

    protected $casts = [
        'credits' => 'integer',
        'debits' => 'integer',
        'balance' => 'integer',
    ];

}
