<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Models\League;
use App\Models\Game;
use App\Jobs\GenerateClubGamesReport;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Enums\Role;
use App\Notifications\ClubReportsAvailable;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ProcessClubReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $region;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region)
    {
        // set report scope
        $this->region = $region;

        if ( Storage::exists($region->club_folder) ){
          // remove old reports
          //Storage::deleteDirectory($region->club_folder, false);
        } else {
          // make sure folders are there
          Storage::makeDirectory($region->club_folder);
        };

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all clubs with games
        $clubs = $this->region->clubs()->get();
        $region = Region::find($this->region->id);

        foreach ($clubs as $c){

          // delete old files
          //Storage::delete(File::glob(storage_path().'/app/'.$this->region->club_folder.'/'.$c->shortname.'*'));

          // build list of report jobs based on format
          $rpt_jobs = array();
          foreach ( $this->region->fmt_club_reports->getFlags() as $rtype  ){
            if ($rtype->hasFlag(ReportFileType::XLSX) or $rtype->hasFlag(ReportFileType::XLS) or $rtype->hasFlag(ReportFileType::ODS)){
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ms_all() );
            } elseif ($rtype->hasFlag(ReportFileType::CSV)){
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_all() );
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_home() );
            } elseif ($rtype->hasFlag(ReportFileType::PDF) or $rtype->hasFlag(ReportFileType::HTML) or $rtype->hasFlag(ReportFileType::ICS)){
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_all() );
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_home() );
              $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_referee() );

              $leagues = Game::where('club_id_home',$c->id)->with('league')->get()->pluck('league.id')->unique();
              foreach ($leagues as $l){
                $rpt_jobs[] = new GenerateClubGamesReport($region, $c, $rtype, ReportScope::ss_club_league(), League::find($l) );
              }
            }
          };

          $batch = Bus::batch($rpt_jobs)
            ->then(function (Batch $batch) use ($c) {
              // All jobs completed successfully...
              if ($c->memberIsA(Role::ClubLead)){
                $clead = $c->members()->wherePivot('role_id', Role::ClubLead)->first();
                $clead->notify(new ClubReportsAvailable($c));
              }
          })->name('Club Reports '.$c->shortname)
            ->onConnection('redis')
            ->onQueue('exports')
            ->dispatch();
        }
    }
}
