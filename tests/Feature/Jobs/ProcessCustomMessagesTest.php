<?php

namespace Tests\Feature\Jobs;

use App\Enums\Role;
use App\Jobs\ProcessCustomMessages;
use App\Jobs\SendCustomMessage;
use App\Models\Message;
use App\Models\Region;
use App\Models\User;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\Bus;
use Tests\SysTestCase;

class ProcessCustomMessagesTest extends SysTestCase
{
    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_send_emails()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();

        // create message (send data tomorrow)
        Message::create([
            'user_id' => User::first()->id,
            'region_id' => $region->id,
            'title' => 'testing auto send',
            'greeting' => 'Hi',
            'body' => 'This is an autosent mail',
            'salutation' => 'bye',
            'to_members' => [Role::ClubLead],
            'send_at' => now()->addDays(10),
        ]
        );

        // test for today (nothing shoud be send)
        $this->travel(9)->days();
        $job_instance = resolve(ProcessCustomMessages::class);
        app()->call([$job_instance, 'handle']);
        Bus::assertNotDispatched(SendCustomMessage::class);
        $this->travelBack();

        // test for next day (1 job shoud be started)
        $this->travel(10)->days();
        $job_instance = resolve(ProcessCustomMessages::class);
        app()->call([$job_instance, 'handle']);
        Bus::assertBatched(function (PendingBatch $batch) {
            return $batch->name == 'Send eMails' &&
                   $batch->jobs->count() === 1;
        });
        $this->travelBack();

        // test for day after tomorrow (nothing shoud be send)
        $this->travel(12)->days();
        $job_instance = resolve(ProcessCustomMessages::class);
        app()->call([$job_instance, 'handle']);
        Bus::assertNotDispatched(SendCustomMessage::class);
        $this->travelBack();
    }
}
