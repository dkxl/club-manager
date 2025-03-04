<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;


class BankAccountModulus implements ValidationRule, DataAwareRule
{

    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];


    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }


    /**
     * Validate a Bank Account and Sort Code modulus
     * Wrapper for Chris Smith's Bank-Modulus library
     * @param $attribute  - bank account attribute name
     * @param $value  - bank account value
     * @param array $parameters - first item equals sort code attribute name
     * @return bool
     */
    public function validateBankAccountModulus($attribute, $value, $parameters)
    {
//        $this->requireParameterCount(1, $parameters, 'bank_account_modulus');
//
//        $account_number = $value;
//        $sort_code = $this->getValue($parameters[0]);
//
//        $modulus = new BankModulus();
//
//        try {
//            $is_valid = $modulus->check($sort_code, $account_number);
//        } catch (SortCodeInvalidException $e) {
//            $is_valid = false;
//        } catch (InvalidArgumentException $e ) {
//            $is_valid = false;
//        }
//
//        return $is_valid;

    }
}
