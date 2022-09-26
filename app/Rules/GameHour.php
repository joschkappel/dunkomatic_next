<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameHour implements Rule
{
    private array $ok_hours = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ok_hours = ['08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'];
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
        $hour_val = explode(':', $value);
        if (count($hour_val) != 2) {
            return false;
        } else {
            return in_array($hour_val[0], $this->ok_hours);
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
