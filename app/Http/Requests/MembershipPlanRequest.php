<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class MembershipPlanRequest extends FormRequest
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
                Rule::unique('membership_plans', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('plan.id')),
            ],
            'free_classes' => 'required|boolean',
            'available' => 'required|boolean',
            'jf_amount' => 'required|decimal:2',  // currency
            'puf_amount' => 'required|decimal:2',
            'dd_amount' => 'required|decimal:2',
            'term_months' => 'required|integer',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
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
                'start_time' => NormalisationServices::toTime($this->get('start_time')),
                'end_time' => NormalisationServices::toTime($this->get('end_time')),
            ]);
    }

}
