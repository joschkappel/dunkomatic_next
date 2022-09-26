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

    /**
     * Ability required to create new entities
     *
     * @var string
     */
    public $cardNewAbility;

    /**
     * HTML id for table
     *
     * @var string
     */
    public $tableId;

    /**
     * HTML table classe
     *
     * @var string
     */
    public $tableClass;

    public function __construct(string $cardTitle, string $tableId = 'table', string $tableClass = 'table-hover table-bordered', string $cardNewAction = null, string $cardNewTitle = '', string $cardNewAbility = '')
    {
        $this->cardTitle = $cardTitle;
        $this->tableId = $tableId ?? 'table';
        $this->tableClass = $tableClass ?? 'table-hover table-bordered';
        if ($cardNewAction) {
            $this->cardNewAction = $cardNewAction;
            $this->cardNewTitle = $cardNewTitle;
            $this->cardNewAbility = $cardNewAbility;
        }
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
