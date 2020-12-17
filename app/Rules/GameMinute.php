<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameMinute implements Rule
{
    private $ok_minutes = array();

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ok_minutes = array('00','15','30','45');
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
        $min_val = explode(":",$value);
        return in_array($min_val[1], $this->ok_minutes);
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
