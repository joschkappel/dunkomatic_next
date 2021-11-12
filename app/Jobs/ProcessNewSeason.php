<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Traits\LeagueFSM;

use App\Models\Setting;
use App\Models\League;
use App\Models\User;
use App\Models\Member;
use App\Models\ScheduleEvent;
use App\Models\Region;
use App\Notifications\CheckRegionSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewSeason;

class ProcessNewSeason implements ShouldQueue
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
        Log::info('[JOB][NEW SEASON] started.');
        // determine new season year/year+1
        $cur_year = Carbon::now()->format('Y');
        $next_year = Carbon::now()->addYear()->format('Y');
        $next_season = $cur_year . '/' . Str::substr($next_year, -2);

        $current_season = Setting::where('name', 'season')->first()->value;

        // settings season
        Setting::where('name', 'season')->update(['value' => $next_season]);
        Log::notice('[JOB][NEW SEASON] season name modified.', ['old' => $current_season, 'new' => Setting::where('name', 'season')->first()->value]);

        // reset leagues
        $league = League::all();

        foreach ($league as $l) {
            // delete league clubs (dont delete, as this keeps stable over seasons)
            // $l->clubs()->delete();

            // reset teams
            foreach ($l->teams as $t) {
                //Log::info('new season: reset team '.$l->shortname.' - '.$t->league_char);
                $t->update([
                    'league_char' => null,
                    'league_no' => null,
                    'league_prev' => $l->shortname,
                    'league_id' => null
                ]);
            }
            Log::notice('[JOB][NEW SEASON] teams de-registered.', ['league-id' => $l->id]);

            $this->open_assignment($l);
            $l->games()->delete();
            Log::notice('[JOB][NEW SEASON] league games deleted.', ['league-id' => $l->id]);
        }

        // clean up report folders
        $path = 'exports/' . Str::of($current_season)->replace('/', '_');
        $directories = Storage::allDirectories($path);
        foreach ($directories as $d){
            Storage::deleteDirectory($d);
        }
        Storage::deleteDirectory($path);
        Log::notice('[JOB][NEW SEASON] report folders cleaned.', ['folder' => $path]);
        // create new report folders
        $path = 'exports/' . Str::of($next_season)->replace('/', '_');
        Storage::makeDirectory($path);
        Log::notice('[JOB][NEW SEASON] report folders created.', ['folder' => $path]);

        // move schedules 1 year forward
        // this move weekdays - ScheduleEvent::whereNotNull('game_day')->update(['game_date' => DB::raw('DATE_ADD(game_date, INTERVAL 1 YEAR)')]);
        $schedule_events = ScheduleEvent::all();
        foreach ($schedule_events as $se){
            $weekday = $se->game_date->weekday(); // get weekday of game_date
            $next_weekday = $se->game_date->addYear(1)->weekday(); // get weekday of game_date + 1 year
            // move game_date 1 year ahead but keep weekdays (saturday stays on saturday)
            $year_game_date = $se->game_date->addYear(1)->addDays($weekday - $next_weekday); // add 1 year and difference of weekdays
            $se->update(['game_date'=>$year_game_date]);
        }


        Log::notice('[JOB][NEW SEASON] All schedule events fwdd by 1 year.');

        // move region league state end date 1 year fowrward
        $regions = Region::all();
        foreach ($regions as $r){
            $close_assignment_at = $r->close_assignment_at ?? now();
            $close_assignment_at = $close_assignment_at->addYear(1)->addDays( $close_assignment_at->weekday() - $close_assignment_at->addYear(1)->weekday() );

            $close_registration_at = $r->close_registration_at ?? now();
            $close_registration_at = $close_registration_at->addYear(1)->addDays( $close_registration_at->weekday() - $close_registration_at->addYear(1)->weekday() );

            $close_selection_at = $r->close_selection_at ?? now();
            $close_selection_at = $close_selection_at->addYear(1)->addDays( $close_selection_at->weekday() - $close_selection_at->addYear(1)->weekday() );

            $close_scheduling_at = $r->close_scheduling_at ?? now();
            $close_scheduling_at = $close_scheduling_at->addYear(1)->addDays( $close_scheduling_at->weekday() - $close_scheduling_at->addYear(1)->weekday() );

            $close_referees_at = $r->close_referees_at ?? now();
            $close_referees_at = $close_referees_at->addYear(1)->addDays( $close_referees_at->weekday() - $close_referees_at->addYear(1)->weekday() );

            $r->update(
                ['close_assignment_at' => $close_assignment_at,
                'close_registration_at' => $close_registration_at,
                'close_selection_at' => $close_selection_at,
                'close_scheduling_at' => $close_scheduling_at,
                'close_referees_at' => $close_referees_at]
            );
            $radmins = $r->regionadmin;
            foreach ($radmins as $ra){
                app()->setLocale($ra->user->locale);
                Notification::send($ra, new  CheckRegionSettings($next_season, $r));
                Notification::send($ra->user, new  CheckRegionSettings($next_season, $r));
            }
            Log::info('[NOTIFICATION] check region settings.', ['members' => $radmins->pluck('id')]);
        }
        Log::notice('[JOB][NEW SEASON] region league state dates fwdd by 1 year.');

        // notify region admin on these changes and ask to check/correct


        // send notification
        $users = User::whereNotNull('approved_at')->whereNotNull('email_verified_at')->get();
        Notification::send($users, new NewSeason($next_season));
        Log::info('[NOTIFICATION] new season started.', ['users' => $users->pluck('id')]);

        $members = Member::all();
        Notification::send($members, new NewSeason($next_season));
        Log::info('[NOTIFICATION] new season started.', ['members' => $members->pluck('id')]);
    }
}
