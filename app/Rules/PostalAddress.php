<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PostalAddress implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
//        if (is_null($value)) {
//            return;
//        }

        if (!preg_match( '/^[A-Za-z0-9 \-&#\/.,]+$/', $value) ) {
            $fail('The :attribute may only contain letters, spaces, numbers, ampersand(&), dash(-), forward slash(/), fullstop, or commas');
        };
    }
}
