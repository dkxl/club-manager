<?php

namespace App\Http\Requests;

use App\Rules\PostCode;
use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;


class PostcodeRequest extends FormRequest
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
            'postcode' => [
                'required',
                'string',
                 new PostCode,
            ],
        ];
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                // format as uppercase, no whitespace
                'postcode' => preg_replace('/[^A-Z0-9]/', '', strtoupper($this->get('postcode'))),
            ]);
    }

}
