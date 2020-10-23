<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Region;
use App\Models\User;

use App\Jobs\EmailValidation;
use App\Jobs\MissingLeadCheck;


class ProcessRegionJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

      $regions = Region::all();

      foreach ($regions as $r){
        if (User::regionAdmin($r->code)->exists()){
          EmailValidation::dispatch($r)->delay(now()->addMinutes(1))->onQueue('janitor');
          MissingLeadCheck::dispatch($r)->delay(now()->addMinutes(5))->onQueue('janitor');
        }
      }
    }
}
