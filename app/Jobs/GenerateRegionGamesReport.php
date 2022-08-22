<?php

namespace App\Jobs;

use App\Models\Region;
use App\Enums\ReportFileType;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CalendarComposer;

use App\Exports\RegionGamesReport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateRegionGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $export_folder;
    public string $rpt_name;
    public Region $region;
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
        $this->export_folder = $this->region->region_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->region->code;
        $this->rpt_name .= '_Gesamtplan.';
        $this->rpt_name .= $this->rtype->description;
        $this->rpt_name = Str::replace(' ','-', $this->rpt_name );
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
                return;
            }
        }

        Log::info('[JOB][REGION GAMES REPORTS] started.', [
            'region-id' => $this->region->id,
            'format' => $this->rtype->key,
            'path' => $this->rpt_name]);

        if ($this->rtype->hasFlag(ReportFileType::PDF)) {
            Excel::store(new RegionGamesReport($this->region ), $this->rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
        } elseif ($this->rtype->hasFlag(ReportFileType::ICS)) {
            // do calendar files
            $calendar = CalendarComposer::createRegionCalendar($this->region);
            if ($calendar != null) {
                Storage::put($this->rpt_name, $calendar->get());
            }
        } elseif ($this->rtype->hasFlag(ReportFileType::XLSX)) {
            //Excel::store( (new RegionGamesReport($this->region))->queue($this->rpt_name)->onQueue('region_'.$this->region->id), $this->rpt_name);
            Excel::store( new RegionGamesReport($this->region), $this->rpt_name);
        } else {
            Excel::store( new RegionGamesReport($this->region), $this->rpt_name);
        }


    }
}
