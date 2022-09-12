<?php

namespace App\Jobs;

use App\Models\Region;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\RegionLeagueGamesReport;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GenerateRegionLeaguesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $export_folder;
    public string $rpt_name;
    public Region $region;
    public ReportScope $scope;
    public ReportFileType $rtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, ReportFileType $rtype)
    {
        // set report scope
        $this->region = $region->withoutRelations();
        $this->rtype = $rtype;

        // make sure folders are there
        if ( ! Storage::exists($this->region->region_folder)) {
            // clean folder
            Storage::makeDirectory($this->region->region_folder);
        } ;
        $this->export_folder = $this->region->region_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->region->code;
        $this->rpt_name .= '_Rundenbuch.';
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

        foreach ($this->rtype->getFlags() as $rtype){
            $rpt_name = $this->rpt_name . $rtype->description;
            $rpt_name = Str::replace(' ','-', $rpt_name );

            Log::info('[JOB][REGION LEAGUE GAMES REPORTS] started.', [
                'region-id' => $this->region->id,
                'format' => $rtype->key,
                'path' => $rpt_name
            ]);

            if ($rtype->hasFlag(ReportFileType::PDF)) {
                Excel::store(new RegionLeagueGamesReport($this->region ), $rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
            } elseif ($rtype->hasFlag(ReportFileType::XLSX)) {
                Excel::store( new RegionLeagueGamesReport($this->region), $rpt_name);
            } else {
                Excel::store( new RegionLeagueGamesReport($this->region), $rpt_name);
            }
        }

    }
}
