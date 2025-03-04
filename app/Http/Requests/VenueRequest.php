<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class VenueRequest extends FormRequest
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
            'name' => [
                'required',
                'max:40',
                new AlphaNumSpace,
                Rule::unique('venues', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('venue.id')),
            ],
            'capacity' => 'required|integer',
            'description' => [
                'nullable',
                'max:200',
                'string',
            ],
        ];
    }
}
