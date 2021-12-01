<?php

namespace Tests\Jobs;

use Tests\SysTestCase;

use App\Jobs\ProcessCustomMessages;

use App\Models\Region;
use App\Enums\Role;
use App\Mail\CustomMailMessage;
use App\Notifications\AppActionMessage;
use App\Notifications\CustomDbMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;

class CustomMessageTest extends SysTestCase
{


    /**
     * run job send message to users
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_notify_user_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $msg = Region::where('code','HBVDA')->first()->messages->first();
        $job_instance = resolve(ProcessCustomMessages::class, ['message'=>$msg]);
        app()->call([$job_instance, 'handle']);

        // check that message has beeen sent to users
        Notification::assertSentTo( $msg->region->users, CustomDbMessage::class);
        // check that autor is informed
        Notification::assertSentTo( $msg->user, AppActionMessage::class);
        // check that message is marked as SENT
        $this->assertDatabaseMissing('messages', ['id'=>$msg->id, 'sent_at'=>null]);

    }
    /**
     * run job send message to admin
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_notify_admin_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        Mail::fake();
        Mail::assertNothingSent();
        Mail::assertNothingQueued();

        $msg = Region::where('code','HBVDA')->first()->messages->first();
        $msg->message_destinations->first()->update(['role_id'=> Role::Admin]);
        $job_instance = resolve(ProcessCustomMessages::class, ['message'=>$msg]);
        app()->call([$job_instance, 'handle']);

        // check that email has beeen sent to users
        Mail::assertSent(CustomMailMessage::class);
        Mail::assertNotQueued( CustomMailMessage::class);
        // check that autor is informed
        Notification::assertSentTo( $msg->user, AppActionMessage::class);
        // check that message is marked as SENT
        $this->assertDatabaseMissing('messages', ['id'=>$msg->id, 'sent_at'=>null]);

    }
    /**
     * run job send message to club lead
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_notify_clublead_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        Mail::fake();
        Mail::assertNothingSent();
        Mail::assertNothingQueued();

        $msg = Region::where('code','HBVDA')->first()->messages->first();
        $msg->message_destinations->first()->update(['role_id'=> Role::ClubLead]);
        $job_instance = resolve(ProcessCustomMessages::class, ['message'=>$msg]);
        app()->call([$job_instance, 'handle']);

        // check that email has beeen sent to users
        Mail::assertSent(CustomMailMessage::class);
        Mail::assertNotQueued( CustomMailMessage::class);
        // check that autor is informed
        Notification::assertSentTo( $msg->user, AppActionMessage::class);
        // check that message is marked as SENT
        $this->assertDatabaseMissing('messages', ['id'=>$msg->id, 'sent_at'=>null]);
    }
   /**
     * run job send message to league lead
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_notify_leaguelead_job()
    {
        Notification::fake();
        Notification::assertNothingSent();

        Mail::fake();
        Mail::assertNothingSent();
        Mail::assertNothingQueued();

        $msg = Region::where('code','HBVDA')->first()->messages->first();
        $msg->message_destinations->first()->update(['role_id'=> Role::LeagueLead]);
        $job_instance = resolve(ProcessCustomMessages::class, ['message'=>$msg]);
        app()->call([$job_instance, 'handle']);

        // check that email has beeen sent to users
        Mail::assertSent(CustomMailMessage::class);
        Mail::assertNotQueued( CustomMailMessage::class);
        // check that autor is informed
        Notification::assertSentTo( $msg->user, AppActionMessage::class);
        // check that message is marked as SENT
        $this->assertDatabaseMissing('messages', ['id'=>$msg->id, 'sent_at'=>null]);
    }
}
