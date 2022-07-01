<?php

namespace App\View\Components;

use App\Models\League;

use App\Traits\LeagueTeamManager;
use Illuminate\View\Component;

class LeagueContent extends Component
{
    use LeagueTeamManager;

    /**
     * clubs and/or teams of the league
     */
    public $league_content;
    /**
     * team keys
     */
    public $t_keys;
    /**
     * club keys
     */
    public $c_keys;
    /**
     * percentage of space for each club/team
     */
    public $item_space;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( League $league)
    {
        list($clubteams, $this->t_keys, $this->c_keys) = $this->get_registrations($league);
        $this->league_content = $clubteams->sortBy([['team_league_no','desc'],['team_id','desc'],['club_shortname','asc']]);
        $this->item_space = intval(100 / count($this->league_content));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.league-content');
    }
}
