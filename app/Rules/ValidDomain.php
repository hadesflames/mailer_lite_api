<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDomain implements Rule
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
        if (!stristr($value, '@')) {
            return false;
        }

        $email_parts = explode('@', $value);
        if (count($email_parts) !== 2) {
            return false;
        }

        $domain = $email_parts[1];
        return filter_var(gethostbyname($domain), FILTER_VALIDATE_IP);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email must have a valid domain.';
    }
}
