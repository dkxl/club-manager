<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BaseModel extends Model
{

    use HasUlids, SoftDeletes, HasFactory;

    public $incrementing = false; // primary key is a ULID for most models, not an integer sequence

}
