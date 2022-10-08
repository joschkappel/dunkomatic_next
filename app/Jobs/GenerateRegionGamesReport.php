<?php

namespace App\Jobs;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Exports\RegionGamesReport;
use App\Helpers\CalendarComposer;
use App\Models\Region;
use App\Traits\ReportManager;
use App\Traits\ReportJobStatus;
use App\Traits\ReportVersioning;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GenerateRegionGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ReportManager, ReportJobStatus, ReportVersioning;

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
        if (! Storage::exists($this->region->region_folder)) {
            // clean folder
            Storage::makeDirectory($this->region->region_folder);
        }
        $this->export_folder = $this->region->region_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->region->code;
        $this->rpt_name .= '_Gesamtplan';
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
        $version = $this->get_report_version($this->region, Report::RegionGames());
        // move previous versions
        $this->move_old_report($this->region, $this->export_folder, '_Gesamtplan');

        foreach ($this->rtype->getFlags() as $rtype) {
            $rpt_name = $this->rpt_name.'_v'.$version.'.'.$rtype->description;
            $rpt_name = Str::replace(' ', '-', $rpt_name);

            Log::info('[JOB][REGION GAMES REPORTS] started.', [
                'region-id' => $this->region->id,
                'format' => $rtype->key,
                'path' => $rpt_name,
            ]);

            if ($rtype->hasFlag(ReportFileType::PDF)) {
                Excel::store(new RegionGamesReport($this->region), $rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
            } elseif ($rtype->hasFlag(ReportFileType::ICS)) {
                // do calendar files
                $calendar = CalendarComposer::createRegionCalendar($this->region);
                if ($calendar != null) {
                    Storage::put($rpt_name, $calendar->get());
                }
            } elseif ($rtype->hasFlag(ReportFileType::XLSX)) {
                //Excel::store( (new RegionGamesReport($this->region))->queue($this->rpt_name)->onQueue('region_'.$this->region->id), $this->rpt_name);
                Excel::store(new RegionGamesReport($this->region), $rpt_name);
            } else {
                Excel::store(new RegionGamesReport($this->region), $rpt_name);
            }
        }
    }
}
