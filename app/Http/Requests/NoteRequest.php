<?php

namespace App\Http\Requests;

use App\Services\NormalisationServices;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class NoteRequest extends FormRequest
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
                'required',
                'ulid',
                Rule::exists('members', 'id')
            ],
            'topic' => 'required|integer',
            'note' => 'required|string',
            'alert' => 'integer',
        ];
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            [
                'note' => NormalisationServices::tidyWhiteSpace($this->get('note')),
            ]);
    }
}
