<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Models\League;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\CalendarComposer;
use Illuminate\Support\Facades\Storage;

use App\Exports\ClubGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

class GenerateClubGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $export_folder;
    protected string $rpt_name;
    protected Club $club;
    protected Region $region;
    protected ReportScope $scope;
    protected ReportFileType $rtype;
    protected ?League $league;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, Club $club, ReportFileType $rtype, ReportScope $scope, League $league = null)
    {
        // set report scope
        $this->region = $region;
        $this->club = $club;
        $this->league = $league;
        $this->scope = $scope;
        $this->rtype = $rtype;

        // make sure folders are there
        $this->export_folder = $region->club_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->club->shortname;

        switch ($this->scope->value) {
            case ReportScope::ms_all:
                $this->rpt_name .= '_Gesamtplan.';
                break;
            case ReportScope::ss_club_all:
                $this->rpt_name .= '_Vereinsplan.';
                break;
            case ReportScope::ss_club_home:
                $this->rpt_name .= '_Heimspielplan.';
                break;
            case ReportScope::ss_club_referee:
                $this->rpt_name .= '_Schiriplan.';
                break;
            case ReportScope::ss_club_league:
                $this->rpt_name .= '_' . $this->league->shortname . '_Rundenplan.';
                break;
        }
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
        Log::info('[JOB][CLUB GAMES REPORTS] started.', [
            'region-id' => $this->region->id,
            'club-id' => $this->club->id,
            'league-id' => $this->league->id ?? '',
            'format' => $this->rtype->key,
            'scope' => ReportScope::coerce($this->scope)->key,
            'path' => $this->rpt_name]);

        if ($this->rtype->hasFlag(ReportFileType::PDF)) {
            Excel::store( new ClubGamesExport($this->club->id, $this->scope, (isset($this->league->id)) ? $this->league->id : NULL),
                          $this->rpt_name,
                          null,
                          \Maatwebsite\Excel\Excel::MPDF);
        } elseif ($this->rtype->hasFlag(ReportFileType::ICS)) {
            // do calendar files
            $calendar = null;
            switch ($this->scope->value) {
                case ReportScope::ss_club_all:
                    $calendar = CalendarComposer::createClubCalendar($this->club);
                    break;
                case ReportScope::ss_club_home:
                    $calendar = CalendarComposer::createClubHomeCalendar($this->club);
                    break;
                case ReportScope::ss_club_league:
                    $calendar = CalendarComposer::createClubLeagueCalendar($this->club, $this->league);
                    break;
                case ReportScope::ss_club_referee:
                    $calendar = CalendarComposer::createClubRefereeCalendar($this->club);
                    break;
            }
            if ($calendar != null) {
                Storage::put($this->rpt_name, $calendar->get());
            }
        } else {
            Excel::store(new ClubGamesExport($this->club->id, $this->scope, (isset($this->league->id)) ? $this->league->id : NULL), $this->rpt_name);
        }
    }
}
