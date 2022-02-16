<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Enums\LeagueState;
use App\Models\League;

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
     * The league KPIs
     *
     * @var list
     */
    public $league_kpis;

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
    public function __construct( League $league, string $mode='progressbar')
    {
        $this->currentState = $league->state;
        $this->league_kpis = $league->state_count;
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
