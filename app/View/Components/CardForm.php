<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardForm extends Component
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
     * The form HTTP method to use
     *
     * @var string
     */
    public $formMethod;

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
    public function __construct($cardChangeNote='',
                                $cardTitle,
                                $formAction='',
                                $formMethod='POST',
                                $omitCancel=False,
                                $omitSubmit=False,
                                $colWidth='6',
                                $isMultipart=False)
    {
        $this->cardTitle = $cardTitle;
        $this->formAction = $formAction;
        $this->formMethod = $formMethod;
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
        return view('components.card-form');
    }
}
