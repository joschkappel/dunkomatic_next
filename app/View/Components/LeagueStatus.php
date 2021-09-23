<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Enums\LeagueState;

use Illuminate\Support\Facades\Log;

class LeagueStatus extends Component
{

    /**
     * The current state
     *
     * @var LeagueState
     */
    public $currentState;

    /**
     * The display mode (badge, infobox-icon, progressbar)
     *
     * @var string
     */
    public $mode;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($currentState, $mode='progressbar')
    {
        $this->currentState = LeagueState::coerce($currentState);
        $this->mode = $mode;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.league-status-'.$this->mode);
    }
}
