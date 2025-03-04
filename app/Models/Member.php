<?php

namespace App\Models;

use App\Casts\DateUk;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends BaseModel
{

    // data maps
    public $honorificTypes = [
        'Mr',
        'Mrs',
        'Miss',
        'Ms',
        'Mx',
        'M.',
        'Ind.',
        'Dr',
        'Rev',
        'Prof',
    ];

    public $genderTypes = [
        'male',
        'female',
        'non-binary',
        'other',
    ];


    /*
     * By default, all data is returned as a string. We can override if need be
     */
    protected $casts = [
        'dob' => DateUk::class,
        'med_dec_date' => DateUk::class,
    ];


    // List fields that the model->fill() method can populate
    protected $fillable = [
            'first_name',
            'last_name',
            'email',
            'card_number',
            'dob',
            'address_1',
            'address_2',
            'address_3',
            'address_4',
            'town',
            'county',
            'postcode',
            'phone',
            'gender',
            'honorific',
            'emerg_contact',
            'emerg_phone',
            'med_dec_date',
            'wordpress_id'
    ];


    /*
     * Relationships
     */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    public function contracts()
    {
        return $this->hasMany(Contract::class);   // TODO: how to stop this failing if no contracts exist?
    }


    public function notes()
    {
        return $this->hasMany(Note::class);
    }



    /*
     * View Helpers
     */

    public function getMembershipType() : string
    {
        $currentContract =  $this->contracts()->currentContract()->first();
        return ($currentContract) ? $currentContract->membership_plan->name : 'Non-Member';
    }


    public function getMembershipState() : string
    {
        $currentContract =  $this->contracts()->currentContract()->first();
        return ($currentContract) ? $currentContract->stateString() : 'None';
    }


    public function getFullName()
    {

    	// Use array_filter to remove empty name elements. This stops implode()
	    // using the glue character even if one or both of the names are empty

        $full_name = implode(' ', array_filter([$this->first_name, $this->last_name]));
        return $full_name;
    }




}
