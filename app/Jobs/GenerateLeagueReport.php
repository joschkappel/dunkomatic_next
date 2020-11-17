<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Enums\ReportFileType;


use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Exports\LeagueGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLeagueReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $export_folder;
    private $rpt_name;
    private $region;
    private $scope;
    private $league;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, League $league, $scope='ALL')
    {
        // set report scope
        $this->region = $region;
        $this->league = $league;
        $this->scope = $scope;

        // make sure folders are there
        $this->export_folder = $region->league_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->league->shortname.'_games';

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


      foreach ( $this->region->fmt_league_reports->getFlags() as $rtype  ){
        switch ( $this->scope ){
          case 'ALL':
            if ($rtype->hasFlag(ReportFileType::PDF)){
              Excel::store(new LeagueGamesExport($this->league->id), $this->rpt_name.'_all.'.$rtype->description, NULL, \Maatwebsite\Excel\Excel::MPDF );
            } else {
              Excel::store(new LeagueGamesExport($this->league->id), $this->rpt_name.'_all.'.$rtype->description );
            }
            break;
        }
      }
    }
}
