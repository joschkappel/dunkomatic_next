<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SliderRange implements Rule
{
    public $min;
    public $max;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
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
        $slider_vals = explode(";", $value);
        if ($slider_vals[0] < $this->min){
            return false;
        }
        if ($slider_vals[1] > $this->max){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.sliderrange', ['min' => $this->min, 'max' => $this->max]);
    }
}
