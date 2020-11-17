<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Jobs\GenerateClubGamesReport;
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
        $clubs = Club::clubRegion($this->region->code)->get();
        $region = Region::find($this->region->id);

        foreach ($clubs as $c){

          // delete old files
          //Storage::delete(File::glob(storage_path().'/app/'.$this->region->club_folder.'/'.$c->shortname.'*'));

          $batch = Bus::batch([
            [
              new GenerateClubGamesReport($region, $c, 'ALL' ),
              new GenerateClubGamesReport($region, $c, 'HOME' ),
              new GenerateClubGamesReport($region, $c, 'REFEREE' ),
            ]
          ])->then(function (Batch $batch) use ($c) {
              // All jobs completed successfully...
              if ($c->memberships()->isRole(Role::ClubLead)->exists()){
                $clead = $c->memberships()->isRole(Role::ClubLead)->first()->member;
                $clead->notify(new ClubReportsAvailable($c));
              }
          })->catch(function (Batch $batch, Throwable $e) {
              // First batch job failure detected...
          })->finally(function (Batch $batch) {
              // The batch has finished executing...
          })->name('Club Reports '.$c->shortname)
            ->onConnection('redis')
            ->onQueue('exports')
            ->dispatch();
        }
    }
}
