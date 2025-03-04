<?php

namespace App\Http\Requests;

use App\Models\CheckIn;
use App\Models\Member;
use App\Rules\AlphaNumSpace;
use App\Rules\MembershipCard;
use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CheckInRequest extends FormRequest
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
            'member_id' => [
                'ulid',
                'nullable',
                Rule::exists('members', 'id')
            ],
            'card_number' => [ // only required if member id is empty
                'max:40',
                'required_without:member_id',
                new MembershipCard,
            ],
        ];
    }


}
