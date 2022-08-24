<?php

namespace App\Jobs;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

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
        Log::notice('[JOB][DB CLEANUP] creating DB backup.');

        Artisan::call('model:prune');
        // drop all outdated (one week) messages;
        // drop all users (incl messages and members) that have been rejected four weeks ago;
        // drop all users (incl messages and members) that havent verfied their email since a week;

        // drop all read notifications
        // $old_notifs = DatabaseNotification::whereDate('read_at', '<',now()->subWeek())->delete();
        // Log::notice('[JOB][DB CLEANUP] deleting read notifications.', ['count' => $old_notifs]);
        // drop old invitations
        $old_invites = Invitation::whereDate('created_at', '<',now()->subWeek())->delete();
        Log::notice('[JOB][DB CLEANUP] deleting old invitations.', ['count' => $old_invites]);

    }
}
