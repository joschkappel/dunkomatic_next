<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GameHour implements Rule
{
    private $ok_hours = array();

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->ok_hours = array('08','09','10','11','12','13','14','15','16','17','18','19','20');
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
      $hour_val = explode(":",$value);
      return in_array($hour_val[0], $this->ok_hours);
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
