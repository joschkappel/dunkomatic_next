<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Region;
use App\Models\Club;
use App\Models\User;
use App\Models\Member;

class GameOverlaps implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $region;
    protected $region_admin;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region)
    {
      $this->region = $region;
      $region_user = User::regionAdmin($this->region->code)->with('member')->first();
      $this->region_admin = Member::find($region_user['member']->id)->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info('job running: game day validation checks for region '.$this->region->code);
      $clubs = Club::clubRegion($this->region->code)->get();
      $game_slot = $this->region->game_slot;
      $min_slot = $game_slot - 1 ;

      foreach ($clubs as $c){
        $select = 'SELECT distinct ga.id
               FROM games ga
               JOIN games gb on ga.game_time <= date_add(gb.game_time, INTERVAL '.$min_slot.' minute)
                   and date_add(ga.game_time,interval '.$min_slot.' minute) >= gb.game_time
                   and ga.club_id_home=gb.club_id_home and ga.gym_no = gb.gym_no and ga.game_date = gb.game_date
                   and ga.id != gb.id
               WHERE ga.club_id_home='.$c->id.' ORDER BY ga.game_date DESC, ga.club_id_home ASC';

         $ogames = collect(DB::select($select))->pluck('id');


         if ( count($ogames)>0 ){
           Log::info('checking '.$game_slot.' min for '.$c->shortname.' - '.print_r($ogames,true));
         }

      }

    }
}
