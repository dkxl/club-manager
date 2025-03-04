<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use function Symfony\Component\Translation\t;

class Telephone implements ValidationRule
{
    /**
     * Run the validation rules.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $valid = true;

        // Copy the parameter and strip out the spaces
        $telephone = str_replace (' ', '', $value);

        // Remove hyphens - they are not part of a telephone number
        $telephone = str_replace ('-', '', $telephone);

        // Only basic format checking for international numbers (assumes a leading "+")
        if (preg_match('/^\+(?:[0-9]?){5,15}$/',$telephone)) {
            return;
        }

        // Now check that all the characters are digits
        if (!preg_match('/^[0-9]{10,11}$/',$telephone)) {
            $valid = false;
        }

        // If looks ok, also check that the first digit is 0
        if ($valid && !preg_match('/^0[0-9]{9,10}$/',$telephone)) {
            $valid = false;
        }

        // Check the string against the fictional numbers allocated for TV and Radio dramas
        $tnexp[0] =  '/^(0113|0114|0115|0116|0117|0118|0121|0131|0141|0151|0161)(4960)[0-9]{3}$/';
        $tnexp[1] =  '/^02079460[0-9]{3}$/';
        $tnexp[2] =  '/^01914980[0-9]{3}$/';
        $tnexp[3] =  '/^02890180[0-9]{3}$/';
        $tnexp[4] =  '/^02920180[0-9]{3}$/';
        $tnexp[5] =  '/^01632960[0-9]{3}$/';
        $tnexp[6] =  '/^07700900[0-9]{3}$/';
        $tnexp[7] =  '/^08081570[0-9]{3}$/';
        $tnexp[8] =  '/^09098790[0-9]{3}$/';
        $tnexp[9] =  '/^03069990[0-9]{3}$/';

        foreach ($tnexp as $regexp) {
            if ($valid && preg_match($regexp,$telephone, $matches))
                $valid = false;
        }

        // Finally, check that the telephone number is appropriate for UK allocations.
        if ($valid && !preg_match('/^(01|02|03|05|070|071|072|073|074|075|07624|077|078|079)[0-9]+$/',$telephone))
            $valid = false;

        // Call the $fail closure if one of the validity checks failed
        if (!$valid) {
            $fail('The :attribute does not look like a valid telephone number');
        };
    }
}
