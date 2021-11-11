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

        if ($league->schedule()->exists()  and  $league->league_size()->exists()) {
            Log::info('observer: league created - set status ASSIGNMENT',['league-id'=>$league->id]);
            $this->open_assignment($league);
        } else {
            Log::info('observer: league created - set status SETUP',['league-id'=>$league->id]);
            $this->open_setup($league);
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
                Log::info('observer: league updated - set status SETUP',['league-id'=>$league->id]);
                $this->open_setup($league);
            }
        }

        if ($league->schedule()->exists()  and  $league->league_size()->exists() and ($league->state->is(LeagueState::Setup))) {
            if (!$league->state->is(LeagueState::Assignment())) {
                Log::info('observer: league updated - set status ASSIGNMENT',['league-id'=>$league->id]);
                $this->open_assignment($league);
            }
        }
    }

    /**
     * Handle the League "deleted" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function deleted(League $league)
    {
        //
    }

    /**
     * Handle the League "restored" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function restored(League $league)
    {
        //
    }

    /**
     * Handle the League "force deleted" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function forceDeleted(League $league)
    {
        //
    }
}
