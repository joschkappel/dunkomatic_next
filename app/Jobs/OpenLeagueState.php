<?php

namespace App\Jobs;

use App\Enums\JobFrequencyType;
use App\Enums\LeagueState;
use App\Enums\ReportFileType;
use App\Models\Region;
use App\Models\User;
use App\Notifications\LeagueStateOpened;
use App\Traits\LeagueFSM;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

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

        $scheduling_opened = collect();
        $scheduling_not_opened = collect();
        $selection_opened = collect();
        $selection_not_opened = collect();
        $users = User::whereIs('regionadmin')->get();

        // for each region
        foreach ( Region::with('leagues')->get() as $r){
            // get regionadmins for notifications
            $radmins = collect();
            foreach($users as $u) {
                if ($u->can('access',$r)){
                    $radmins->push($u);
                }
            }

            // set close date defaults to future if empty
            $open_selection = $r->open_selection_at ??  Carbon::now()->addDays(2);
            $open_scheduling = $r->open_scheduling_at ??  Carbon::now()->addDays(2);

            // if open_selection is today then change state for all region league
            if ($open_selection->isToday()){
                Log::info('[JOB][OPEN LEAGUE STATES] opening selection for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ( ($l->state->is(LeagueState::Registration())) and (!$l->is_custom)){
                        $this->open_char_selection($l->load('clubs','teams'), true);
                        $selection_opened->push($l);
                    } else {
                        $selection_not_opened->push($l);
                        Log::warning('[JOB][OPEN LEAGUE STATES] league in wrong state or custom.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
                Notification::send($radmins, new LeagueStateOpened( __('league.action.open.selection') , $selection_opened, $selection_not_opened));
            }



            // if open_scheduling is today then change state for all region leagues
            if ($open_scheduling->isToday()){
                Log::info('[JOB][OPEN LEAGUE STATES] opening scheduling for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Freeze())){
                        $this->open_game_scheduling($l->load('clubs','teams'), true);
                        $scheduling_opened->push($l);
                    } else {
                        $scheduling_not_opened->push($l);
                        Log::warning('[JOB][OPEN LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                    }
                }
                if (count($scheduling_opened)>0){
                    $r->update([
                        'job_game_overlaps' => true,
                        'job_game_notime' => true
                    ]);
                };

                Notification::send($radmins, new LeagueStateOpened(__('league.action.open.scheduling'), $scheduling_opened, $scheduling_not_opened));
            }

        }

    }
}
