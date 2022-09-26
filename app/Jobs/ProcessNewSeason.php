<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Member;
use App\Models\Region;
use App\Models\ScheduleEvent;
use App\Models\Setting;
use App\Models\Team;
use App\Models\User;
use App\Notifications\CheckRegionSettings;
use App\Notifications\NewSeason;
use App\Traits\LeagueFSM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $next_season = $cur_year.'/'.Str::substr($next_year, -2);

        $current_season = Setting::where('name', 'season')->first()->value;

        // settings season
        Setting::where('name', 'season')->update(['value' => $next_season]);
        Log::notice('[JOB][NEW SEASON] season name modified.', ['old' => $current_season, 'new' => Setting::where('name', 'season')->first()->value]);

        // reset leagues
        $league = League::with('teams')->get();

        foreach ($league as $l) {
            $this->restart_league($l);
        }

        // clean up report folders
        $path = config('dunkomatic.folders.export').'/'.Str::of($current_season)->replace('/', '_');
        $directories = Storage::allDirectories($path);
        foreach ($directories as $d) {
            Storage::deleteDirectory($d);
        }
        Storage::deleteDirectory($path);
        Log::notice('[JOB][NEW SEASON] report folders cleaned.', ['folder' => $path]);

        // create new report folders
        $path = config('dunkomatic.folders.export').'/'.Str::of($next_season)->replace('/', '_');
        Storage::makeDirectory($path);
        Log::notice('[JOB][NEW SEASON] report folders created.', ['folder' => $path]);

        // move schedules 1 year forward
        // this move weekdays - ScheduleEvent::whereNotNull('game_day')->update(['game_date' => DB::raw('DATE_ADD(game_date, INTERVAL 1 YEAR)')]);
        $schedule_events = ScheduleEvent::all();
        foreach ($schedule_events as $se) {
            $weekday = $se->game_date->weekday(); // get weekday of game_date
            $next_weekday = $se->game_date->addYear()->weekday(); // get weekday of game_date + 1 year
            // move game_date 1 year ahead but keep weekdays (saturday stays on saturday)
            $year_game_date = $se->game_date->addYear()->addDays($weekday - $next_weekday); // add 1 year and difference of weekdays
            $se->update(['game_date' => $year_game_date]);
        }

        Log::notice('[JOB][NEW SEASON] All schedule events fwdd by 1 year.');

        // move region league state end date 1 year fowrward
        $regions = Region::with('regionadmins')->get();
        foreach ($regions as $r) {
            $open_selection_at = $r->open_selection_at ?? now();
            $open_selection_at = $open_selection_at->addYear()->addDays($open_selection_at->weekday() - $open_selection_at->addYear()->weekday());

            $open_scheduling_at = $r->open_scheduling_at ?? now();
            $open_scheduling_at = $open_scheduling_at->addYear()->addDays($open_scheduling_at->weekday() - $open_scheduling_at->addYear()->weekday());

            $close_selection_at = $r->close_selection_at ?? now();
            $close_selection_at = $close_selection_at->addYear()->addDays($close_selection_at->weekday() - $close_selection_at->addYear()->weekday());

            $close_scheduling_at = $r->close_scheduling_at ?? now();
            $close_scheduling_at = $close_scheduling_at->addYear()->addDays($close_scheduling_at->weekday() - $close_scheduling_at->addYear()->weekday());

            $close_referees_at = $r->close_referees_at ?? now();
            $close_referees_at = $close_referees_at->addYear()->addDays($close_referees_at->weekday() - $close_referees_at->addYear()->weekday());

            $r->update(
                ['open_selection_at' => $open_selection_at,
                    'open_scheduling_at' => $open_scheduling_at,
                    'close_selection_at' => $close_selection_at,
                    'close_scheduling_at' => $close_scheduling_at,
                    'close_referees_at' => $close_referees_at, ]
            );
            if ($r->regionadmins()->exists()) {
                $radmins = $r->regionadmins()->get();
                foreach ($radmins as $ra) {
                    Notification::send($ra, new  CheckRegionSettings($next_season, $r));
                    Notification::send($ra->user, new  CheckRegionSettings($next_season, $r));
                }
                Log::info('[NOTIFICATION] check region settings.', ['members' => $radmins->pluck('id')]);
            }
        }
        Log::notice('[JOB][NEW SEASON] region league state dates fwdd by 1 year.');

        // hard reset for all teams:
        Team::whereNotNull('league_no')->update(['league_id' => null, 'league_no' => null]);

        // notify region admin on these changes and ask to check/correct

        // send notification
        $users = User::whereNotNull('approved_at')->whereNotNull('email_verified_at')->get()->chunk(100);
        foreach ($users as $chunk) {
            Notification::send($chunk, new NewSeason($next_season));
            Log::info('[NOTIFICATION] new season started.', ['users' => $chunk->pluck('id')]);
        }

        $members = Member::all()->chunk(100);
        foreach ($members as $chunk) {
            Notification::send($chunk, new NewSeason($next_season));
            Log::info('[NOTIFICATION] new season started.', ['members' => $chunk->pluck('id')]);
        }
    }
}
