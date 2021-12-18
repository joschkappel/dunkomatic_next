<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardHeader extends Component
{
    /**
     * The card header title.
     *
     * @var string
     */
    public $title;

    /**
     * Icon name
     *
     * @var string
     */
    public $icon;

    /**
     * number ot things for badge
     *
     * @var string
     */
    public $count;
    /**
     * show card colleapse/expand tool
     *
     * @var boolean
     */
    public $showtools;


    public function __construct($title, $count=0, $icon='', $showtools=true )
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->count = $count;
        $this->showtools = $showtools;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-header');
    }
}
