<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardList extends Component
{
    /**
     * The card header title.
     *
     * @var string
     */
    public $cardTitle;

    /**
     * The route to create a new object
     *
     * @var string
     */
    public $cardNewAction;    

    /**
     * Button name to create a new object
     *
     * @var string
     */
    public $cardNewTitle; 


    public function __construct($cardTitle, $cardNewAction=null, $cardNewTitle='' )
    {
        $this->cardTitle = $cardTitle;
        if ($cardNewAction){
            $this->cardNewAction = $cardNewAction;
        }
        $this->cardNewTitle = $cardNewTitle;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-list');
    }
}