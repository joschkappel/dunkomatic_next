<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Enums\ReportFileType;
use App\Models\Region;
use App\Jobs\GenerateLeagueGamesReport;
use App\Notifications\LeagueReportsAvailable;
use App\Enums\Role;

use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessLeagueReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Region $region;

    /**
     * Create a new job instance.
     *
     * @param Region $region
     * @return void
     *
     */
    public function __construct(Region $region)
    {
        // set report scope
        $this->region = $region;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ( Storage::exists($this->region->league_folder)) {
            // clean folder
            Storage::deleteDirectory($this->region->league_folder);
        } ;
        // and recretae
        Storage::makeDirectory($this->region->league_folder);

        if ( Storage::exists($this->region->teamware_folder)) {
            // delete old files
            Storage::deleteDirectory($this->region->teamware_folder);
        };
        // and recreate folder
        Storage::makeDirectory($this->region->teamware_folder);

        // get all leagues with games
        $leagues = $this->region->leagues;
        $region = $this->region;
        $rtypes = $this->region->fmt_league_reports->getFlags();
        // add ICS format as default
        $rtypes[] = ReportFileType::ICS();

        Log::info('[JOB] kicking off league report batch jobs.', ['region-id' => $region->id]);

        foreach ($leagues as $l) {

            // delete old files
            //Storage::delete(File::glob(storage_path().'/app/'.$this->region->league_folder.'/'.$l->shortname.'*'));

            $rpt_jobs = array();
            foreach ($rtypes as $rtype) {
                $rpt_jobs[] = new GenerateLeagueGamesReport($region, $l, $rtype);
            };
            // add teamware
            $rpt_jobs[] = new GenerateTeamwareReport($l);

            $note = new LeagueReportsAvailable($l);

            $batch = Bus::batch($rpt_jobs)
                ->then(function (Batch $batch) use ($l, $note, $region) {
                    // All jobs completed successfully...
                    if ($l->state->is(LeagueState::Live())){
                        if ($l->memberIsA(Role::LeagueLead())) {
                            $llead = $l->members()->wherePivot('role_id', Role::LeagueLead)->first();
                            $llead->notify($note);
                            Log::info('[NOTIFICATION] league reports available.', ['member-id' => $llead->id]);
                        }
                    }

                    // update region
                    $region->update([
                        'job_league_reports_running' => false,
                        'job_league_reports_lastrun_at' => now()
                    ]);

                })
                ->finally(function (Batch $batch) use ($region){
                    if ($batch->failedJobs >  0){
                        $region->update(['job_league_reports_lastrun_ok' => false]);
                    } else {
                        $region->update(['job_league_reports_lastrun_ok' => true]);
                    }
                })
                ->name('League Reports ' . $l->shortname)
                ->onConnection('redis')
                ->onQueue('region_'.$this->region->id)
                ->dispatch();
        }
    }
}
