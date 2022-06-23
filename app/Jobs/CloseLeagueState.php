<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Models\Region;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloseLeagueState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LeagueFSM;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[JOB][CLOSE LEAGUE STATES] started.');

        // for each region
        foreach ( Region::with('leagues')->get() as $r){
            // set close date defaults to future if empty
            $close_selection = $r->close_selection_at ??  Carbon::now()->addDays(2);
            $close_scheduling = $r->close_scheduling_at ??  Carbon::now()->addDays(2);
            $close_referees = $r->close_referees_at ??  Carbon::now()->addDays(2);

            // if close_selection is today then change state for all region leagues
            if ($close_selection->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing selection and opening freeze for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Selection())){
                        $this->freeze_league($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
            }
            // if close_scheduöing is today then change state for all region leagues
            if ($close_scheduling->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing scheduling and opening referees assignment for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Scheduling())){
                        $this->open_ref_assignment($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
            }
            // if close_referees is today then change state for all region leagues
            if ($close_referees->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing referee assignments and go live for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Referees())){
                        $this->golive_league($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
            }
        }
    }
}
