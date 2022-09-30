<?php

namespace Tests\Jobs;

use App\Jobs\EmailValidation;
use App\Models\Region;
use App\Notifications\InvalidEmail;
use Illuminate\Support\Facades\Notification;
use Tests\SysTestCase;

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

        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first();

        $job_instance = resolve(EmailValidation::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($region_admin, InvalidEmail::class);
    }
}
