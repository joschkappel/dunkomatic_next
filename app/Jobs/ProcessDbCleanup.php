<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;
use App\Models\Message;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
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
        $aweekago = Carbon::today()->subDays(7)->toDateString();
        $amonthago = Carbon::today()->subDays(30)->toDateString();

        // drop all outdated (one week) messages;
        $old_msgs = Message::whereDate('sent_at', '<', $aweekago)->get();
        foreach ($old_msgs as $om) {
            $om->message_destinations()->delete();
            $om->delete();
        }
        Log::notice('[JOB][DB CLEANUP] deleting messages.', ['count' => count($old_msgs)]);

        // drop all users (incl messages and members) that have been rejected a week ago;
        $old_users = User::whereDate('rejected_at', '<', $aweekago)->get();
        foreach ($old_users as $ou) {
            $ou->delete();
        }
        Log::notice('[JOB][DB CLEANUP] deleting rejected users.', ['count' => count($old_users)]);

        // drop all users (incl messages and members) that havent verfied their email since a month;
        $old_users = User::whereNull('email_verified_at')->whereDate('created_at', '<', $amonthago)->get();
        foreach ($old_users as $ou) {
            $ou->delete();
        }
        Log::notice('[JOB][DB CLEANUP] deleting users with unverified email.', ['count' => count($old_users)]);

        // drop all read notifications
        $old_notifs = DatabaseNotification::whereDate('read_at', '<', $aweekago)->delete();
        Log::notice('[JOB][DB CLEANUP] deleting read notifications.', ['count' => $old_notifs]);
    }
}
