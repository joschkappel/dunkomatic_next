<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Models\Region;
use App\Traits\LeagueFSM;

use Illuminate\Support\Carbon;

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

        // set close date defaults to future if empty
        $close_assignment = $this->region->close_assignment_at ??  Carbon::now()->nextWeekday();
        $close_registration = $this->region->close_registration_at ??  Carbon::now()->nextWeekday();
        $close_selection = $this->region->close_selection_at ??  Carbon::now()->nextWeekday();
        $close_scheduling = $this->region->close_scheduling_at ??  Carbon::now()->nextWeekday();
        $close_referees = $this->region->close_referees_at ??  Carbon::now()->nextWeekday();


        foreach ($leagues as $l){
            // check if all clubs are assigned
            if ($l->state->is( LeagueState::Assignment())){
                if ( ( $l->clubs->count() == $l->size ) or ( $close_assignment < now()) ){
                    $this->close_assignment($l);
                }
            } elseif ($l->state->is( LeagueState::Registration())){
                if (( $l->clubs->count() == $l->teams->count() ) or ( $close_registration < now()) ){
                    $this->close_registration($l);
                }
            } elseif ($l->state->is( LeagueState::Selection())){
                if ( ( $l->teams->count() == $l->teams->whereNotNull('league_no')->count() ) or ( $close_selection < now()) ){
                    $this->close_selection($l);
                }
            } elseif ($l->state->is( LeagueState::Scheduling())){
                if ( (( $l->games_notime->count() == 0 ) and ($l->games_noshow->count() == 0 )) or ( $close_scheduling < now()) ){
                    $this->close_scheduling($l);
                }
            } elseif ($l->state->is( LeagueState::Referees())){
                if ( ( $l->games_noreferee->count() == 0 )  or ( $close_referees < now()) ){
                    $this->close_scheduling($l);
                }
            }
        }
    }
}
