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
use App\Models\Member;


class GameNotScheduled implements ShouldQueue
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
      $this->region_admin = $region->regionadmin()->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info('job running: unscheduled games check for region '.$this->region->code);
      $clubs = Club::clubRegion($this->region->code)->get();

      foreach ($clubs as $c){
         $select = 'SELECT distinct ga.id
               FROM games ga
               WHERE ga.club_id_home='.$c->id.' and ga.game_date is not null and ga.game_time is null ORDER BY ga.game_date DESC, ga.club_id_home ASC';

         $ogames = collect(DB::select($select))->pluck('id');

         if ( count($ogames)>0 ){
           Log::info('checking unscheduled for '.$c->shortname.' - '.print_r($ogames,true));
         }

      }
    }
}
