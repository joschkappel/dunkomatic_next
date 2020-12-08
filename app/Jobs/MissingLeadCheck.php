<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Models\Region;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Enums\Role;

use App\Notifications\MissingLead;

class MissingLeadCheck implements ShouldQueue
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
      Log::info('job running: missing lead checks for region '.$this->region->code);

      $clubs_nolead = array();
      $leagues_nolead = array();

      $clubs = $this->region->clubs()->get();
      foreach ($clubs as $c){
        if (!$c->memberships()->isRole(Role::ClubLead)->exists()){
          $clubs_nolead[] = $c->shortname;
          // Log::debug('lead missing for '.$c->shortname);
        }
      }

      $leagues = $this->region->leagues()->get();
      foreach ($leagues as $l){
        if (!$l->memberships()->isRole(Role::LeagueLead)->exists()){
          $leagues_nolead[] = $l->shortname;
          // Log::debug('lead missing for '.$l->shortname);
        }
      }

      if ( (count($clubs_nolead) > 0 ) or (count($leagues_nolead)>0 )){
        $this->region_admin->notify( new MissingLead($clubs_nolead, $leagues_nolead) );
      }
    }
}
