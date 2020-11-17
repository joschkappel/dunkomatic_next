<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Jobs\GenerateLeagueReport;
use App\Notifications\LeagueReportsAvailable;
use App\Enums\Role;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLeagueReports implements ShouldQueue
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

        if (Storage::exists($region->league_folder)){
          // remove old reports
          //Storage::deleteDirectory($region->league_folder, false);
        } else {
          // make sure folders are there
          Storage::makeDirectory($region->league_folder);
        };

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // get all leagues with games
        $leagues = League::leagueRegion($this->region->code)->get();
        foreach ($leagues as $l){

          // delete old files
          //Storage::delete(File::glob(storage_path().'/app/'.$this->region->league_folder.'/'.$l->shortname.'*'));

          $batch = Bus::batch([
              new GenerateLeagueReport(Region::find($this->region->id), $l, 'ALL'),
          ])->then(function (Batch $batch) use ($l) {
              // All jobs completed successfully...
              if ($l->memberships()->isRole(Role::LeagueLead)->exists()){
                $llead = $l->memberships()->isRole(Role::LeagueLead)->first()->member;

                $llead->notify(new LeagueReportsAvailable($l));
              }
          })->catch(function (Batch $batch, Throwable $e) {
              // First batch job failure detected...
          })->finally(function (Batch $batch) {
              // The batch has finished executing...
          })->name('League Reports '.$l->shortname)
            ->onConnection('redis')
            ->onQueue('exports')
            ->dispatch();
        }
    }
}
