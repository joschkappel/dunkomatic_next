<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * The modal ID.
     *
     * @var string
     */
    public $modalId;

    /**
     * The modal title.
     *
     * @var string
     */
    public $modalTitle;

    /**
     * The modal HTTP method to use
     *
     * @var string
     */
    public $modalMethod;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $modalId, string $modalTitle, string $modalMethod = 'POST')
    {
        $this->modalId = $modalId;
        $this->modalTitle = $modalTitle;
        $this->modalMethod = $modalMethod;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
