<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Models\League;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Str;
use App\Exports\ClubGamesExport;
use App\Exports\ClubHomeGamesExport;
use App\Exports\ClubRefereeGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateClubGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $export_folder;
    protected $rpt_name;
    protected $club;
    protected $region;
    protected $scope;
    protected $rtype;
    protected $league;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, Club $club, ReportFileType $rtype, ReportScope $scope, League $league = NULL )
    {
        // set report scope
        $this->region = $region;
        $this->club = $club;
        $this->league = $league;
        $this->scope = $scope->value;
        $this->rtype = $rtype;

        // make sure folders are there
        $this->export_folder = $region->club_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->club->shortname;

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

      if ($this->rtype->hasFlag(ReportFileType::PDF)){
        Excel::store(new ClubGamesExport($this->club->id, new ReportScope($this->scope), $this->league->id), $this->rpt_name, NULL, \Maatwebsite\Excel\Excel::MPDF );
      } else {
        Excel::store(new ClubGamesExport($this->club->id, new ReportScope($this->scope), (isset($this->league->id))?$this->league->id : NULL ), $this->rpt_name );
      }
    }
}
