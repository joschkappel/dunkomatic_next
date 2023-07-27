<?php

namespace App\Jobs;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Exports\LeagueGamesReport;
use App\Helpers\CalendarComposer;
use App\Models\League;
use App\Models\Region;
use App\Traits\ReportJobStatus;
use App\Traits\ReportManager;
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

class GenerateLeagueGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ReportManager, ReportJobStatus, ReportVersioning;

    protected string $export_folder;

    protected string $rpt_name;

    protected Region $region;

    protected ReportScope $scope;

    protected League $league;

    protected ReportFileType $rtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, League $league, ReportFileType $rtype)
    {
        // set report scope
        $this->region = $region;
        $this->league = $league;
        $this->rtype = $rtype;

        // make sure folders are there
        if (!Storage::exists($this->region->league_folder)) {
            // clean folder
            Storage::makeDirectory($this->region->league_folder);
        }

        $this->export_folder = $region->league_folder;
        $this->rpt_name = $this->export_folder . '/' . Str::slug($this->league->shortname);
        $this->rpt_name .= '_' . Report::LeagueGames()->getReportFilename();
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
        $version = $this->get_report_version($this->region, Report::LeagueGames());
        // move previous versions
        // $this->move_old_report($this->region, $this->export_folder, '_' . Report::LeagueGames()->getReportFilename());

        foreach ($this->rtype->getFlags() as $rtype) {
            $rpt_name = $this->rpt_name . '_v' . $version . '.' . $rtype->description;
            $rpt_name = Str::replace(' ', '-', $rpt_name);

            Log::info('[JOB][LEAGUE GAMES REPORTS] started.', [
                'region-id' => $this->region->id,
                'league-id' => $this->league->id,
                'format' => $rtype->key,
                'path' => $rpt_name,
            ]);

            if ($rtype->hasFlag(ReportFileType::PDF)) {
                Excel::store(new LeagueGamesReport($this->league->id), $rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
            } elseif ($rtype->hasFlag(ReportFileType::ICS)) {
                // do calendar files
                $calendar = CalendarComposer::createLeagueCalendar($this->league);
                if ($calendar != null) {
                    Storage::put($rpt_name, $calendar->get());
                }
            } else {
                Excel::store(new LeagueGamesReport($this->league->id), $rpt_name);
            }
        }
    }
}
