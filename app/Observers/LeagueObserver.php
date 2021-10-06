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
        if ( isset($league->schedule_id)  and isset($league->league_size_id ) ) {
             $this->open_assignment($league);
        } else {
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
        if ( !isset($league->schedule_id)  or  !isset($league->league_size_id) ){
            if (! $league->state->is(LeagueState::Setup() )){
                $this->open_setup($league);
            }
        }

        if ( isset($league->schedule_id)  and  isset($league->league_size_id) and ($league->state->is(LeagueState::Setup))  ){
            if ( ! $league->state->is(LeagueState::Assignment() ) ) {
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
