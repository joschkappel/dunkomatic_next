<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ConfirmDeletion extends Component
{

    /**
     * The modal ID.
     *
     * @var string
     */
    public $modalId;

    /**
     * The modal confimration text
     *
     * @var string
     */
    public $modalConfirm;

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
     * The type of the entity to be deleted
     *
     * @var string
     */
    public $deleteType;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( string $modalId,
                                 string $modalTitle,
                                 string $modalConfirm,
                                 string $deleteType,
                                 string $modalMethod='DELETE')
    {
        $this->modalId = $modalId;
        $this->modalTitle = $modalTitle;
        $this->modalConfirm = $modalConfirm;
        $this->deleteType = $deleteType;
        $this->modalMethod = $modalMethod;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.confirm-deletion');
    }
}
