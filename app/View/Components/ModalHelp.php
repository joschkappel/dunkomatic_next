<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalHelp extends Component
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
     * The modal size (xs, md, lg,...)
     *
     * @var string
     */
    public $modalSize;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $modalId, string $modalTitle, string $modalSize='md')
    {
        $this->modalId = $modalId;
        $this->modalTitle = $modalTitle;
        $this->modalSize = $modalSize;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal-help');
    }
}
