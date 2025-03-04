<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BankSortCode implements ValidationRule
{
    /**
     * Run the validation rule.
     * Requires Chris Smith's Bank-Modulus library
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }


    /**
     * Validate the sort code format & length
     * Requires Chris Smith's Bank-Modulus library
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateBankSortCode($attribute, $value, $parameters)
    {
//        try {
//            $sort_code = SortCode::create($value);
//            $is_valid = (bool) ($sort_code instanceof SortCode);
//        } catch (SortCodeInvalidException $e) {
//            $is_valid = false;
//        } catch (InvalidArgumentException $e ) {
//            $is_valid = false;
//        }
//
//        return $is_valid;
    }

}
