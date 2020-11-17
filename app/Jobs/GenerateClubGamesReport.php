<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Enums\ReportFileType;

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

    private $export_folder;
    private $rpt_name;
    private $club;
    private $region;
    private $scope;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, Club $club, $scope='ALL')
    {
        // set report scope
        $this->region = $region;
        $this->club = $club;
        $this->scope = $scope;

        // make sure folders are there
        $this->export_folder = $region->club_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->club->shortname.'_games';

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


      foreach ( $this->region->fmt_club_reports->getFlags() as $rtype  ){
        switch ( $this->scope ){
          case 'ALL':
            if ($rtype->hasFlag(ReportFileType::PDF)){
              Excel::store(new ClubGamesExport($this->club->id), $this->rpt_name.'_all.'.$rtype->description, NULL, \Maatwebsite\Excel\Excel::MPDF );
            } else {
              Excel::store(new ClubGamesExport($this->club->id), $this->rpt_name.'_all.'.$rtype->description );
            }
            break;
          case 'HOME':
            Excel::store(new ClubHomeGamesExport($this->club->id), $this->rpt_name.'_home.'.$rtype->description);
            break;
          case 'REFEREE':
            Excel::store(new ClubRefereeGamesExport($this->club->id,$this->club->shortname ), $this->rpt_name.'_referee.'.$rtype->description);
            break;
        }
      }
    }
}
