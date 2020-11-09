<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Jobs\GenerateLeagueReport;

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
          $batch = Bus::batch([
              new GenerateLeagueReport(Region::find($this->region->id), $l, 'html'),
              new GenerateLeagueReport(Region::find($this->region->id), $l, 'xlsx'),
              new GenerateLeagueReport(Region::find($this->region->id), $l, 'pdf'),
          ])->then(function (Batch $batch) {
              // All jobs completed successfully...
              // send notification
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
