<?php

namespace App\Jobs;

use App\Enums\ReportFileType;
use App\Models\Region;
use App\Jobs\GenerateRegionGamesReport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class ProcessRegionReport implements ShouldQueue
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
    public function __construct($region)
    {
        // set report scope
        $this->region = Region::find($region);

        if (! Storage::exists($this->region->league_folder)) {
            // make sure folders are there
            Storage::makeDirectory($this->region->league_folder);
        };

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $rtypes = $this->region->fmt_league_reports->getFlags();
        // add ICS format as default
        $rtypes[] = ReportFileType::ICS();

        Log::info('[JOB] kicking off region report jobs.', ['region-id' => $this->region->id]);

        $rpt_jobs = array();
        foreach ($rtypes as $rtype) {
            $rpt_jobs[] = new GenerateRegionGamesReport($this->region, $rtype);
        };

        $batch = Bus::batch($rpt_jobs)
            ->name('Region Reports ' . $this->region->code)
            ->onConnection('redis')
            ->onQueue('exports')
            ->dispatch();

    }
}
