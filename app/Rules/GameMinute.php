<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameMinute implements Rule
{
    private array $ok_minutes = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ok_minutes = ['00', '15', '30', '45'];
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
        $min_val = explode(':', $value);
        if (count($min_val) != 2) {
            return false;
        } else {
            return in_array($min_val[1], $this->ok_minutes);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.gameminute');
    }
}
