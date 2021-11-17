<?php

namespace App\Jobs;

use App\Models\Region;
use App\Jobs\GenerateLeagueGamesReport;
use App\Notifications\LeagueReportsAvailable;
use App\Enums\ReportScope;
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

class ProcessLeagueReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $region;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region)
    {
        // set report scope
        $this->region = $region;

        if (Storage::exists($region->league_folder)) {
            // remove old reports
            //Storage::deleteDirectory($region->league_folder, false);
        } else {
            // make sure folders are there
            Storage::makeDirectory($region->league_folder);
        };
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // get all leagues with games
        $leagues = $this->region->leagues;
        $region = $this->region;
        Log::info('[JOB] kicking off league report batch jobs.', ['region-id' => $region->id]);

        foreach ($leagues as $l) {

            // delete old files
            //Storage::delete(File::glob(storage_path().'/app/'.$this->region->league_folder.'/'.$l->shortname.'*'));

            $rpt_jobs = array();
            foreach ($this->region->fmt_league_reports->getFlags() as $rtype) {
                $rpt_jobs[] = new GenerateLeagueGamesReport($region, $l, $rtype, ReportScope::ms_all());
            };

            $batch = Bus::batch($rpt_jobs)
                ->then(function (Batch $batch) use ($l) {
                    // All jobs completed successfully...
                    if ($l->memberIsA(Role::LeagueLead)) {
                        $llead = $l->members()->wherePivot('role_id', Role::LeagueLead)->first();

                        $llead->notify(new LeagueReportsAvailable($l));
                        Log::info('[NOTIFICATION] league reports available.', ['member-id' => $llead->id]);
                    }
                })->name('League Reports ' . $l->shortname)
                ->onConnection('redis')
                ->onQueue('exports')
                ->dispatch();
        }
    }
}
