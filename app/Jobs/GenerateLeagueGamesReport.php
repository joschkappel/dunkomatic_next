<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\CalendarComposer;
use Illuminate\Support\Facades\Storage;

use App\Exports\LeagueGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class GenerateLeagueGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $export_folder;
    protected $rpt_name;
    protected $region;
    protected $scope;
    protected $league;
    protected $rtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, League $league, ReportFileType $rtype, ReportScope $scope)
    {
        // set report scope
        $this->region = $region;
        $this->league = $league;
        $this->scope = $scope->value;
        $this->rtype = $rtype;

        // make sure folders are there
        $this->export_folder = $region->league_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->league->shortname;

        switch ($this->scope) {
          case ReportScope::ms_all:
            $this->rpt_name .= '_games.';
            break;
          case ReportScope::ss_club_all:
            $this->rpt_name .= '_games_all.';
            break;
          case ReportScope::ss_club_home:
            $this->rpt_name .= '_games_home.';
            break;
          case ReportScope::ss_club_referee:
            $this->rpt_name .= '_games_referee.';
            break;
          case ReportScope::ss_club_league:
            $this->rpt_name .= '_'.$this->league->shortname.'_games.';
            break;
        }
        $this->rpt_name .= $this->rtype->description;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      if ($this->batch()->cancelled()) {
        // Detected cancelled batch...

        return;
      }
      Log::info('[JOB][LEAGUE GAMES REPORTS] started.', ['region-id' => $this->region->id, 'league-id'=>$this->league->id]);

      foreach ( $this->region->fmt_league_reports->getFlags() as $rtype  ){
        switch ( $this->scope ){
          case ReportScope::ms_all:
            if ($rtype->hasFlag(ReportFileType::PDF)){
              Excel::store(new LeagueGamesExport($this->league->id, new ReportScope($this->scope)), $this->rpt_name, NULL, \Maatwebsite\Excel\Excel::MPDF );
            } elseif ($rtype->hasFlag(ReportFileType::ICS)){
              // do calendar files
              $calendar = CalendarComposer::createLeagueCalendar($this->league);
              if ($calendar != null){
                Storage::put($this->rpt_name, $calendar->get());
              }
            } else {
              Excel::store(new LeagueGamesExport($this->league->id, new ReportScope($this->scope)), $this->rpt_name );
            }
            break;
        }
      }

    }
}
