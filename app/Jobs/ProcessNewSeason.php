<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Models\Game;
use App\Models\League;

class ProcessNewSeason implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        // determine new season year7year+1
        $cur_year = Carbon::now()->format('Y');

        $next_year = Carbon::now()->addYear()->format('Y');
        $next_season = $cur_year . '/' . Str::substr($next_year,-2);

        // settings season
        Setting::where('name','season')->update(['value' => $next_season]);

        // clear games
        Game::truncate();

        // reset leagues
        $league = League::all();

        foreach ($league as $l){
          // delete league clubs (dont delete, as this keeps stable over seasons)
          // League::find($l->id)->clubs()->delete();

          // reset teams
          $teams = League::find($l->id)->teams;
          foreach ($teams as $t){
            //Log::info('new season: reset team '.$l->shortname.' - '.$t->league_char);
            $t->update(['league_char' => null,
                        'league_no' => null,
                        'league_prev' => $l->shortname ]);
          }
        }

        // clean up report folders


    }
}
