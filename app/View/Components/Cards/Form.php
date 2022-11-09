<?php

namespace App\View\Components\Cards;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * The card header title.
     *
     * @var string
     */
    public $cardTitle;

    /**
     * The card header note
     *
     * @var string
     */
    public $cardChangeNote;

    /**
     * The form action / route to use
     *
     * @var string
     */
    public $formAction;

    /**
     * disable buttons
     *
     * @var bool
     */
    public $disabled;

    /**
     * Omit the cancel button
     *
     * @var bool
     */
    public $omitCancel;

    /**
     * Omit the submitl button
     *
     * @var bool
     */
    public $omitSubmit;

    /**
     * column / form width
     *
     * @var string
     */
    public $colWidth;

    /**
     * for file upload form
     *
     * @var bool
     */
    public $isMultipart;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $cardChangeNote = '',
                                string $cardTitle = 'no title',
                                string $formAction = '',
                                bool $disabled = true,
                                bool $omitCancel = false,
                                bool $omitSubmit = false,
                                string $colWidth = '6',
                                bool $isMultipart = false)
    {
        $this->cardTitle = $cardTitle;
        $this->formAction = $formAction;
        $this->disabled = $disabled;
        $this->omitCancel = $omitCancel;
        $this->omitSubmit = $omitSubmit;
        $this->colWidth = $colWidth;
        $this->isMultipart = $isMultipart;
        $this->cardChangeNote = $cardChangeNote;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cards.form');
    }
}
