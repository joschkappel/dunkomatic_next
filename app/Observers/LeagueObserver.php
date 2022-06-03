<?php

namespace App\Observers;

use App\Models\League;
use App\Enums\LeagueState;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Log;

class LeagueObserver
{
    use LeagueFSM;

    /**
     * Handle the League "creating" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function created(League $league)
    {
        if ($league->state == null) {
            if ($league->schedule()->exists()  and  $league->league_size()->exists()) {
                Log::info('[OBSERVER] league created - set status ASSIGNMENT', ['league-id' => $league->id]);
                $this->start_league($league);
            } else {
                Log::info('[OBSERVER] league created - set status SETUP', ['league-id' => $league->id]);
                $this->close_league($league);
            }
        }
    }

    /**
     * Handle the League "updating" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function updated(League $league)
    {
        if (!$league->schedule()->exists()  or  !$league->league_size()->exists()) {
            if (!$league->state->is(LeagueState::Setup())) {
                Log::info('[OBSERVER] league updated - set status SETUP', ['league-id' => $league->id]);
                $this->close_league($league);
            }
        }

        if ($league->schedule()->exists()  and  $league->league_size()->exists() and ($league->state->is(LeagueState::Setup))) {
            if (!$league->state->is(LeagueState::Registration())) {
                Log::info('[OBSERVER] league updated - set status REGISTRATION', ['league-id' => $league->id]);
                $this->start_league($league);
            }
        }
    }
}
