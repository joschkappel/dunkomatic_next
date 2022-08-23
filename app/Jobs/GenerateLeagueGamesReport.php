<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\CalendarComposer;
use Illuminate\Support\Facades\Storage;

use App\Exports\LeagueGamesReport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

class GenerateLeagueGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $this->export_folder = $region->league_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->league->shortname;
        $this->rpt_name .= '_Rundenplan.';
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
                // Detected cancelled batch...
                return;
            }
        }

        Log::info('[JOB][LEAGUE GAMES REPORTS] started.', [
            'region-id' => $this->region->id,
            'league-id' => $this->league->id,
            'format' => $this->rtype->key,
            'path' => $this->rpt_name]);

        if ($this->rtype->hasFlag(ReportFileType::PDF)) {
            Excel::store(new LeagueGamesReport($this->league->id ), $this->rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
        } elseif ($this->rtype->hasFlag(ReportFileType::ICS)) {
            // do calendar files
            $calendar = CalendarComposer::createLeagueCalendar($this->league);
            if ($calendar != null) {
                Storage::put($this->rpt_name, $calendar->get());
            }
        } else {
            Excel::store(new LeagueGamesReport($this->league->id), $this->rpt_name);
        }

    }
}
