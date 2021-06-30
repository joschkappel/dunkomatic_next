<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Models\Region;
use App\Models\League;
use App\Traits\LeagueFSM;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLeagueStateChanges implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LeagueFSM;

    private $region;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region)
    {
        // set report scope
        $this->region = $region;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // check for auo-close assignemnts
        $leagues = $this->region->leagues;

        foreach ($leagues as $l){
            // check if all clubs are assigned
            if ($l->isInState( LeagueState::Assignment())){
                if ( $l->clubs->count() == $l->size ){
                    $this->close_assignment($l);
                }
            } elseif ($l->isInState( LeagueState::Registration())){
                if ( $l->clubs->count() == $l->teams->count() ){
                    $this->close_registration($l);
                }
            } elseif ($l->isInState( LeagueState::Selection())){
                if ( $l->teams->count() == $l->teams->whereNotNull('league_no')->count() ){
                    $this->close_selection($l);
                }
            } elseif ($l->isInState( LeagueState::Scheduling())){
                if (( $l->games_notime->count() == 0 ) and ($l->games_noshow->count() == 0 )){
                    $this->close_scheduling($l);
                }
            }            
        }
    }
}
