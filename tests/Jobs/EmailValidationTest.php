<?php

namespace Tests\Unit;

use App\Models\Region;

use Tests\SysTestCase;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;

use App\Jobs\EmailValidation;
use App\Notifications\InvalidEmail;

class EmailValidationTest extends SysTestCase
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

        $region = Region::where('code','HBVDA')->first();
        $region_admin = $region->regionadmin()->first();

        $job_instance = resolve(EmailValidation::class,['region'=>$region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($region_admin, InvalidEmail::class);
    }
}
