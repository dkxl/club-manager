<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FullName implements ValidationRule
{
    /**
     * Run the validation rule.
     * Permit spaces, hyphen, ampersand and apostrophe in bank account names
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match( '/^[A-Za-z &\-\'.,]+$/', $value) ) {
            $fail('The :attribute may only contain letters, spaces, ampersand(&), dash(-), apostrophe, full stop, or commas');
        };
    }
}
