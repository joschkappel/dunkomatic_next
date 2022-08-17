<?php

namespace App\Jobs;

use App\Enums\ReportFileType;
use App\Models\Region;
use App\Jobs\GenerateRegionGamesReport;
use App\Jobs\GenerateRegionLeaguesReport;
use App\Jobs\GenerateRegionContactsReport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batch;
use Illuminate\Support\Str;

class ProcessRegionReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Region $region;
    public $queuename;

    /**
     * Create a new job instance.
     *
     * @param Region $region
     * @return void
     *
     */
    public function __construct($region)
    {
        // set report scope
        $this->region = $region;
        $this->queuename = 'region_'.$region->id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // remove old files
        Storage::delete(
            collect(Storage::allFiles($this->region->region_folder))
            ->filter( function ($v, $k){
                return Str::contains($v, 'Gesamtplan') and Str::contains($v, $this->region->code);
             })->toArray()
        );

        $rtypes = $this->region->fmt_league_reports->getFlags();
        // add ICS format as default
        $rtypes[] = ReportFileType::ICS();

        Log::info('[JOB] kicking off region report jobs.', ['region-id' => $this->region->id]);

        $rpt_jobs = array();
        foreach ($rtypes as $rtype) {
            $rpt_jobs[] = new GenerateRegionGamesReport($this->region, $rtype);
            if ( ($rtype != ReportFileType::ICS()) and ($rtype != ReportFileType::PDF())){
                $rpt_jobs[] = new GenerateRegionLeaguesReport($this->region, $rtype);
                if ($this->region->is_top_level){
                    $rpt_jobs[] = new GenerateRegionContactsReport($this->region, $rtype);
                }
            }
        };

        $region = $this->region;

        $batch = Bus::batch($rpt_jobs)
            ->then(function (Batch $batch) use ($region) {
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
            ->name('Region Reports ' . $this->region->code)
            ->onConnection('redis')
            ->onQueue($this->queuename)
            ->dispatch();

    }
}
