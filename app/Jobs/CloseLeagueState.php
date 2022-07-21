<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Models\User;
use App\Models\Club;
use App\Models\Region;
use App\Traits\LeagueFSM;
use App\Enums\JobFrequencyType;
use App\Enums\ReportFileType;
use App\Enums\Role;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Notifications\LeagueStateClosed;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\GametimeMissing;

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
        $freeze_opened = collect();
        $freeze_not_opened = collect();
        $referees_opened = collect();
        $referees_not_opened = collect();
        $live_opened = collect();
        $live_not_opened = collect();

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
            $close_selection = $r->close_selection_at ??  Carbon::now()->addDays(8);
            $close_scheduling = $r->close_scheduling_at ??  Carbon::now()->addDays(8);
            $close_referees = $r->close_referees_at ??  Carbon::now()->addDays(8);

            // if close_selection is today then change state for all region leagues
            if ($close_selection->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing selection and opening freeze for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Selection())){
                        $this->freeze_league($l);
                        $freeze_opened->push($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                        $freeze_not_opened->push($l);
                    }
                }
                Notification::send($radmins, new LeagueStateClosed( __('league.action.close.selection') , $freeze_opened, $freeze_not_opened));
            }
            // if close_scheduöing is today then change state for all region leagues
            if ($close_scheduling->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing scheduling and opening referees assignment for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Scheduling())){
                        $this->open_ref_assignment($l);
                        $referees_opened->push($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                        $referees_not_opened->push($l);
                    }
                }
                if (count($referees_not_opened)==0){
                    $r->update([
                        'job_game_overlaps' => false,
                        'job_game_notime' => false,
                        'job_league_reports' => JobFrequencyType::daily(),
                        'fmt_league_reports' => ReportFileType::XLSX()
                    ]);
                };
                Notification::send($radmins, new LeagueStateClosed( __('league.action.close.scheduling') , $referees_opened, $referees_not_opened));
            } elseif ($close_scheduling->subDays(2)->isToday()  ){
                // on 2 days from now scheduling will be closed, send notification
                $clubs = collect();
                // collect all clubs
                foreach ( $r->leagues as $l){
                    $clubs = $clubs->concat($this->get_unscheduled_games_clubs($l));
                }
                $clubs = $clubs->unique();
                // get club  leads
                $members = collect();
                foreach ($clubs as $c){
                    $members = $members->concat( Club::find($c)->members()->wherePivot('role_id', Role::ClubLead)->get());
                }
                // send notification
                if ($members->count() > 0){
                    $r->load('regionadmins');
                    $to_chunks = $members->chunk(60);
                    foreach ($to_chunks as $i => $chunk){
                        Mail::to($chunk ?? [])
                            ->cc( $r->regionadmins )
                            ->send(new GametimeMissing(2) );
                        Log::notice('[JOB][CLOSE LEAGUE STATES] gametime missing reminder eMail sent.', ['to_count' => ( ($chunk ?? collect())->count() ) ]);
                    }
                }
            }
            // if close_referees is today then change state for all region leagues
            if ($close_referees->isToday()){
                Log::info('[JOB][CLOSE LEAGUE STATES] closing referee assignments and go live for region.',['region-id'=>$r->id]);
                foreach ( $r->leagues as $l){
                    if ($l->state->is(LeagueState::Referees())){
                        $this->golive_league($l);
                        $live_opened->push($l);
                    } else {
                        Log::warning('[JOB][CLOSE LEAGUE STATES] league in wrong state.',['league-id'=>$l->id, 'state'=>$l->state]);
                        $live_not_opened->push($l);
                    }
                }
                if (count($live_not_opened)==0){
                    $r->update([
                        'job_game_overlaps' => false,
                        'job_game_notime' => false,
                        'job_club_reports' => JobFrequencyType::never(),
                        'job_league_reports' => JobFrequencyType::never()
                    ]);
                };
                Notification::send($radmins, new LeagueStateClosed( __('league.action.close.referees') , $live_opened, $live_not_opened));
            }
        }
    }
}
