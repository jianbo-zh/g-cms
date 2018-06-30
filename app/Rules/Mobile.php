<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Mobile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^13[\d]{9}$|^14[5-9]{1}\d{8}$|^15[^4]{1}\d{8}$|^16[6]{1}\d{8}$|^17[0-8]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$/', $value) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be mobile.';
    }
}
