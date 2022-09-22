<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

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
        // first dump the DB
        Artisan::call('db:backup -c');
        Log::info('[JOB][DB CLEANUP] creating DB backup.');

        Artisan::call('queue:prune-failed');
        Log::notice('[JOB][DB CLEANUP] pruning failed jobs.');

        Artisan::call('queue:prune-batches');
        Log::notice('[JOB][DB CLEANUP] pruning failed batches.');

        Artisan::call('authentication-log:purge');
        Log::notice('[JOB][DB CLEANUP] pruning authentication logs.');

        // use the model prune for:
        Artisan::call('model:prune');
        // drop all outdated (one week) messages;
        // drop all users (incl messages and members) that have been rejected four weeks ago;
        // drop all users (incl messages and members) that havent verfied their email since a month;
        // drop all inviations that are older than a week
        // delete all members that dont have any memberships

        // drop audits older than 2 months
        $old_audits = Audit::whereDate('created_at', '<',now()->subMonths(2))->delete();
        Log::notice('[JOB][DB CLEANUP] deleting old audits.', ['count' => $old_audits]);

        // drop all read notifications
        // $old_notifs = DatabaseNotification::whereDate('read_at', '<',now()->subWeek())->delete();
        // Log::notice('[JOB][DB CLEANUP] deleting read notifications.', ['count' => $old_notifs]);

    }
}
