<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;
use App\Jobs\GenerateClubReport;

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
        foreach ($clubs as $c){
          $batch = Bus::batch([
            //  new GenerateClubReport(Region::find($this->region->id), $c, 'pdf'),
              new GenerateClubReport(Region::find($this->region->id), $c, 'html'),
              new GenerateClubReport(Region::find($this->region->id), $c, 'xlsx'),
          ])->then(function (Batch $batch) {
              // All jobs completed successfully...
              // send notification
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
