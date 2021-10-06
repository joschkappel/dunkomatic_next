<?php

namespace App\Observers;

use App\Models\League;
use App\Enums\LeagueState;

use Illuminate\Support\Facades\Log;

class LeagueObserver
{
    /**
     * Handle the League "creating" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function creating(League $league)
    {
        if ( isset($league->schedule_id)  and isset($league->league_size_id ) ) {
            $league->state = LeagueState::Assignment();
            Log::info('League state set to Assignment');
        } else {
            $league->state = LeagueState::Setup();
            Log::info('League state set to Setup');
        }
    }

    /**
     * Handle the League "updating" event.
     *
     * @param  \App\Models\League  $league
     * @return void
     */
    public function updating(League $league)
    {
        if ( !isset($league->schedule_id)  or  !isset($league->league_size_id) ){
            $league->state = LeagueState::Setup();
            Log::info('League state reset to Setup');
        }

        if ( isset($league->schedule_id)  and  isset($league->league_size_id) and ($league->state->is(LeagueState::Setup))  ){
            $league->state = LeagueState::Assignment();
            Log::info('League state set to Assignment');
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
