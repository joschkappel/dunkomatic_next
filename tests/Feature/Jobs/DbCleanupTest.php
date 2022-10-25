<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessDbCleanup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Tests\SysTestCase;

class DbCleanupTest extends SysTestCase
{
    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        // mark all messages as sent
        $cnt_msg_total = Message::count();
        $cnt_msg = Message::whereNotNull('id')->update(['sent_at' => now()]);
        // mark all users as rejected
        $cnt_user_total = User::count();
        $cnt_user = User::whereNotNull('id')->update(['rejected_at' => now()]);
        // mark all notifications as read
        DatabaseNotification::whereNotNull('id')->update(['read_at' => now()]);

        $this->travel(2)->months();
        $job_instance = resolve(ProcessDbCleanup::class);
        app()->call([$job_instance, 'handle']);

        $this->travelBack();

        $this->assertDatabaseCount('messages', $cnt_msg_total - $cnt_msg);
        $this->assertDatabaseCount('users', $cnt_user_total - $cnt_user);
    }
}
