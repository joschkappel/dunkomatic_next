<?php

namespace App\Jobs;

use App\Enums\Role;
use App\Models\Region;
use App\Notifications\MissingLead;
use App\Traits\LeagueFSM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MissingLeadCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LeagueFSM;

    protected Region $region;

    protected Collection $region_admins;

    /**
     * Create a new job instance.
     *
     * @param  Region  $region
     * @return void
     */
    public function __construct(Region $region)
    {
        $this->region = $region->load('regionadmins');
        $this->region_admins = $region->regionadmins;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[JOB][MISSING ADMINS] started.', ['region-id' => $this->region->id]);

        $clubs_nolead = [];
        $leagues_nolead = [];

        $clubs = $this->region->clubs()->active()->get();
        foreach ($clubs as $c) {
            if (! $c->memberIsA(Role::ClubLead())) {
                $clubs_nolead[] = $c->shortname;
                // Log::debug('lead missing for '.$c->shortname);
            }
        }

        $leagues = $this->region->leagues()->get();
        foreach ($leagues as $l) {
            if ($this->must_have_admin($l) and (! $l->memberIsA(Role::LeagueLead()))) {
                $leagues_nolead[] = $l->shortname;
                // Log::debug('lead missing for '.$l->shortname);
            }
        }

        if ((count($clubs_nolead) > 0) or (count($leagues_nolead) > 0)) {
            Notification::send($this->region_admins, new MissingLead($clubs_nolead, $leagues_nolead));
            Log::warning('[JOB][MISSING ADMINS] clubs without clublead/admin.', ['region-id' => $this->region->id, 'club-ids' => $clubs_nolead]);
            Log::warning('[JOB][MISSING ADMINS] leagues without leaguelead/admin.', ['region-id' => $this->region->id, 'league-ids' => $leagues_nolead]);
        }
    }
}
