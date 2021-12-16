<?php

namespace Tests\Jobs;

use App\Jobs\ProcessDbCleanup;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\Message;
use App\Models\User;

use Tests\SysTestCase;

use Illuminate\Support\Facades\Notification;


class DbCleanupTest extends SysTestCase
{


    /**
     * run job
     *
     * @test
     * @group jobx
     *
     * @return void
     */
    public function run_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        // mark all messages as sent
        $cnt_msg_total = Message::all()->count();
        $cnt_msg = Message::whereNotNull('id')->update(['sent_at'=> now()]);
        // mark all users as rejected
        $cnt_user_total = User::all()->count();
        $cnt_user = User::whereNotNull('id')->update(['rejected_at'=> now()]);
        // mark all notifications as read
        DatabaseNotification::whereNotNull('id')->update(['read_at'=>now()]);


        $this->travel(2)->months();
        $job_instance = resolve(ProcessDbCleanup::class);
        app()->call([$job_instance, 'handle']);

        $this->travelBack();

        $this->assertDatabaseCount('messages', $cnt_msg_total - $cnt_msg);
        $this->assertDatabaseCount('users', $cnt_user_total - $cnt_user);
    }
}
