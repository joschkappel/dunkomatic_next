<?php

namespace App\Jobs;

use App\Models\Region;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\RegionLeagueGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

use Illuminate\Support\Facades\Log;

class GenerateRegionLeaguesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $export_folder;
    protected string $rpt_name;
    protected Region $region;
    protected ReportScope $scope;
    protected ReportFileType $rtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, ReportFileType $rtype)
    {
        // set report scope
        $this->region = $region;
        $this->rtype = $rtype;

        // make sure folders are there
        $this->export_folder = $region->region_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->region->code;
        $this->rpt_name .= '_Rundenbuch.';
        $this->rpt_name .= $this->rtype->description;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch() !== null) {
            if ($this->batch()->cancelled()) {
                // Detected cancelled batch...
                return;
            }
        }

        Log::info('[JOB][REGION LEAGUE GAMES REPORTS] started.', [
            'region-id' => $this->region->id,
            'format' => $this->rtype->key,
            'path' => $this->rpt_name]);

        if ($this->rtype->hasFlag(ReportFileType::PDF)) {
            Excel::store(new RegionLeagueGamesExport($this->region->id ), $this->rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
        } elseif ($this->rtype->hasFlag(ReportFileType::ICS)) {
            return true;
        } else {
            Excel::store(new RegionLeagueGamesExport($this->region->id), $this->rpt_name);
        }


    }
}
