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

class OpenLeagueState implements ShouldQueue
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
        Log::info('[JOB][OPEN LEAGUE STATES] started.');

        // for each region
        foreach ( Region::with('leagues')->get() as $r){
            // set close date defaults to future if empty
            $open_selection = $r->open_selection_at ??  Carbon::now()->addDays(2);
            $open_scheduling = $r->open_scheduling_at ??  Carbon::now()->addDays(2);

            // if open_selection is today then change state for all region league
            if ($open_selection->isToday()){
                Log::info('[JOB][OPEN LEAGUE STATES] opening selection for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Registration())){
                        $this->open_char_selection($l->load('clubs','teams'), true);
                    } else {
                        Log::warning('[JOB][OPEN LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
            }

            // if open_scheduling is today then change state for all region leagues
            if ($open_scheduling->isToday()){
                Log::info('[JOB][OPEN LEAGUE STATES] opening scheduling for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Freeze())){
                        $this->open_game_scheduling($l->load('clubs','teams'), true);
                    } else {
                        Log::warning('[JOB][OPEN LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
            }

        }

    }
}
