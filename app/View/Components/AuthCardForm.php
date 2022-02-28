<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AuthCardForm extends Component
{
    /**
     * column / form width
     *
     * @var string
     */
    public $colWidth;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $colWidth='6')
    {
        $this->colWidth = $colWidth;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.auth-card-form');
    }
}
