<?php

namespace App\Http\Requests;

use App\Rules\AlphaNumSpace;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserAdminRequest extends FormRequest
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
                'max:80',
                new AlphaNumSpace,
                Rule::unique('users', 'name')
                    ->ignore($this->route('user.id')),
            ],
            'email' => [
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->route('user.id')),
            ],
            'admin' => 'required|boolean',
            'staff' => 'required|boolean',
            'sales' => 'required|boolean',
        ];
    }
}
