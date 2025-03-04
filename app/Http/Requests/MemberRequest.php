<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\Rules\FullName;
use App\Rules\MembershipCard;
use App\Rules\PostalAddress;
use App\Rules\PostCode;
use App\Rules\Surname;
use App\Rules\AlphaDashSpace;
use App\Rules\Telephone;
use App\Services\NormalisationServices;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class MemberRequest extends FormRequest
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
     *
     * Ignore the current record when checking email, card_num and fullname for uniqueness
     *
     */
    public function rules() : array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:40',
                new AlphaDashSpace,
                Rule::unique('members', 'first_name')
                    ->where('last_name', $this->get('last_name'))
                    ->whereNull('deleted_at')
                    ->ignore($this->route('member.id')),
            ],
            'last_name' => [
                'required',
                'max:40',
                new Surname,
            ],
            'email' => [
                'email',
                'max:120',
                Rule::unique('members', 'email')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('member.id')),
            ],
            'card_number' => [
                'max:40',
                'nullable',
                new MembershipCard,
                Rule::unique('members', 'card_number')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('member.id')),
            ],
            'dob'   => 'required|date',
            'address_1' => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'address_2' => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'address_3' => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'address_4' => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'town'  => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'county' => [
                'max:80',
                'nullable',
                new PostalAddress,
            ],
            'postcode' => [
                'nullable',
                new PostCode,
            ],
            'phone' => [
                'required',
                new Telephone,
            ],
            'gender' => 'alpha_num|max:10|nullable',
            'honorific'  => 'alpha_num|max:10|nullable',
            'emerg_contact' => [
                'max:80',
                'nullable',
                new FullName,
            ],
            'emerg_phone' => [
                'nullable',
                new Telephone,
            ],
            'med_dec_date' => 'date|nullable'
        ];
    }


    /**
     * Prepare data for validation
     */
    public function prepareForValidation(): void
    {
       $this->merge(
           [
               'first_name' => NormalisationServices::tidyName($this->get('first_name')),
               'last_name' => NormalisationServices::tidyName($this->get('last_name')),
           ]);
    }


    public function messages() :array {
        return [
            'first_name.unique' => 'Full name (first name + last name) already exists',
        ];
    }


}
