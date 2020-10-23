<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\Region;
use Illuminate\Notifications\DatabaseNotification;

class ProcessDbCleanup implements ShouldQueue
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
      // drop all outdated messages;
      $aweekago = Carbon::today()->subDays(7)->toDateString();
      $old_msgs = Message::whereDate('sent_at','<', $aweekago)->get();
      foreach ($old_msgs as $om){
        $om->destinations->delete();
        $om->delete();
      }
      // drop all read notifications
      $old_notifs = DatabaseNotification::whereDate('read_at','<', $aweekago)->delete();
    }

}
