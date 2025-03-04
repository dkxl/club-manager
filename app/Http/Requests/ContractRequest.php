<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ContractRequest extends FormRequest
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
            'plan_id' => [
                'required',
                'ulid',
                Rule::exists('membership_plans', 'id'),
            ],
            'member_id' => [
                'required',
                'ulid',
                Rule::exists('members', 'id')
            ],
            'state' => 'required|integer|digits_between:0,4',
            'start_date' => 'required|date',
            'end_date' => 'date|after:start_date|nullable',
            'jf_amount' => 'required|decimal:2',  // currency
            'puf_amount' => 'required|decimal:2',
            'dd_amount' => 'required|decimal:2',
            'dd_day' => 'integer|digits_between:1,31|nullable',
            'dd_first' => 'date|nullable',
            'dd_last' => 'date|nullable',
            'canx_date' => 'date|nullable',
            'checked' => 'boolean',
        ];
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                'jf_amount' => NormalisationServices::toCurrency($this->get('jf_amount')),
                'puf_amount' => NormalisationServices::toCurrency($this->get('puf_amount')),
                'dd_amount' => NormalisationServices::toCurrency($this->get('dd_amount')),
            ]);
    }

}
