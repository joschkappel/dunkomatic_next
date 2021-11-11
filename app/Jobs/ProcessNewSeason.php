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
use App\Models\Game;
use App\Models\League;
use App\Models\User;
use App\Models\Member;

use Illuminate\Support\Facades\Log;
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
        Log::info('[JOB][NEW SEASON] started.', ['region-id' => $this->region->id]);
        // determine new season year7year+1
        $cur_year = Carbon::now()->format('Y');

        $next_year = Carbon::now()->addYear()->format('Y');
        $next_season = $cur_year . '/' . Str::substr($next_year,-2);

        // settings season
        Log::notice('[JOB][NEW SEASON] season name modified.', ['region-id' => $this->region->id, 'old'=> Setting::where('name','season') , 'new'=>$next_season]);
        Setting::where('name','season')->update(['value' => $next_season]);


         // reset leagues
        $league = League::all();

        foreach ($league as $l){
          // delete league clubs (dont delete, as this keeps stable over seasons)
          // $l->clubs()->delete();

          // reset teams
          foreach ($l->teams as $t){
            //Log::info('new season: reset team '.$l->shortname.' - '.$t->league_char);
            $t->update(['league_char' => null,
                        'league_no' => null,
                        'league_prev' => $l->shortname,
                        'league_id' => null ]);
          }
          Log::notice('[JOB][NEW SEASON] teams de-registered.', ['region-id' => $this->region->id, 'league-id'=>$l->id]);

          $this->open_assignment($l);
          $l->games()->delete();
          Log::notice('[JOB][NEW SEASON] league games deleted.', ['region-id' => $this->region->id, 'league-id'=>$l->id]);

        }

        // clean up report folders
        Log::notice('[JOB][NEW SEASON] report folders cleanedd.', ['region-id' => $this->region->id]);

        // move schedules 1 year forward
        Log::notice('[JOB][NEW SEASON] schedules fwd by 1 year.', ['region-id' => $this->region->id]);

        // move region league state end date 1 year fowrward
        Log::notice('[JOB][NEW SEASON] region league state dates fwd by 1 year.', ['region-id' => $this->region->id]);

        // notify region admin on these cahgnes and ask to check/correct


        // send notification
        $users = User::whereNotNull('approved_at')->whereNotNull('email_verified_at')->get();
        Notification::send( $users, new NewSeason($next_season));
        Log::info('[NOTIFICATION] new season started.', [ 'users'=>$users->pluck('id')]);

        $members = Member::all();
        Notification::send( $members, new NewSeason($next_season));
        Log::info('[NOTIFICATION] new season started.', [ 'members'=>$members->pluck('id')]);

    }
}
