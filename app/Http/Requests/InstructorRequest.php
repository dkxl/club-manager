<?php

namespace App\Http\Requests;

use App\Rules\AlphaDashSpace;
use App\Rules\Telephone;

use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InstructorRequest extends FormRequest
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
                new AlphaDashSpace,
                Rule::unique('instructors', 'name')
                    ->whereNull('deleted_at')
                    ->ignore($this->route('instructor.id')),
            ],
            'email' => [
                'email',
                'max:120',
                'nullable',
            ],
            'phone' => [
                'required',
                new Telephone,
            ],
            'available' => 'required|boolean',
            'skills' => 'string|nullable',
        ];
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                'skills' => NormalisationServices::tidyWhiteSpace($this->get('skills')),
            ]);
    }

}
