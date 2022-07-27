<?php

namespace App\Jobs;

use App\Enums\ReportFileType;
use App\Models\Region;
use App\Jobs\GenerateRegionGamesReport;
use App\Jobs\GenerateRegionLeaguesReport;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            $rpt_jobs[] = new GenerateRegionLeaguesReport($this->region, $rtype);
        };

        $batch = Bus::batch($rpt_jobs)
            ->name('Region Reports ' . $this->region->code)
            ->onConnection('redis')
            ->onQueue('exports')
            ->dispatch();

    }
}
