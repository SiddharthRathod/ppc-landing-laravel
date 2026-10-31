<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * A phone number as people type it: "+966 55 111 6121", "055-111-6121", "(055) 111 6121".
 * - only digits, spaces, dashes, dots and brackets, with an optional + at the start
 * - 8 to 15 digits (15 is the international maximum, E.164; fewer than 8 is not a real number)
 * The same check runs in the browser (resources/js/landing.js).
 */
class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = (string) $value;
        $digits = strlen(preg_replace('/\D/', '', $value));

        if (! preg_match('/^\+?[\d\s\-().]+$/', $value) || $digits < 8 || $digits > 15) {
            $fail('Enter a valid phone number with 8 to 15 digits, e.g. +966 55 111 6121.');
        }
    }
}
