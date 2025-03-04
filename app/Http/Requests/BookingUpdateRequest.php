<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Booking;


class BookingUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * The validation rules
     */
    public function rules() : array
    {

        return [
            'state' => [
                'nullable',
                Rule::in(Booking::$bookingStates)
            ],
            'comments' => 'string|nullable',
        ];
    }

}
