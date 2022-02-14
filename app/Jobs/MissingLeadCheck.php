<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Region;
use App\Models\Member;
use App\Enums\Role;

use App\Notifications\MissingLead;

class MissingLeadCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Region $region;
    protected ?Member $region_admin;

    /**
     * Create a new job instance.
     *
     * @param Region $region
     * @return void
     *
     */
    public function __construct(Region $region)
    {
      $this->region = $region;
      $this->region_admin = $region->regionadmins->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Log::info('[JOB][MISSING ADMINS] started.', ['region-id' => $this->region->id]);

      $clubs_nolead = array();
      $leagues_nolead = array();

      $clubs = $this->region->clubs()->get();
      foreach ($clubs as $c){
        if (!$c->memberIsA(Role::ClubLead())){
          $clubs_nolead[] = $c->shortname;
          // Log::debug('lead missing for '.$c->shortname);
        }
      }

      $leagues = $this->region->leagues()->get();
      foreach ($leagues as $l){
        if (!$l->memberIsA(Role::LeagueLead())){
          $leagues_nolead[] = $l->shortname;
          // Log::debug('lead missing for '.$l->shortname);
        }
      }

      if ( (count($clubs_nolead) > 0 ) or (count($leagues_nolead)>0 )){
        $this->region_admin->notify( new MissingLead($clubs_nolead, $leagues_nolead) );
        Log::warning('[JOB][MISSING ADMINS] clubs without clublead/admin.', ['region-id' => $this->region->id, 'club-ids'=>$clubs_nolead ]);
        Log::warning('[JOB][MISSING ADMINS] leagues without leaguelead/admin.', ['region-id' => $this->region->id, 'league-ids'=>$leagues_nolead ]);
        Log::info('[NOTIFICATION][MEMBER] missing admins.', ['member-id' => $this->region_admin->id]);
      }
    }
}
