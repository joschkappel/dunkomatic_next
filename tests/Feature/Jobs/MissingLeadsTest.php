<?php

namespace Tests\Jobs;

use App\Jobs\MissingLeadCheck;
use App\Models\Region;
use App\Notifications\MissingLead;
use Illuminate\Support\Facades\Notification;
use Tests\SysTestCase;

class MissingLeadsTest extends SysTestCase
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

        $job_instance = resolve(MissingLeadCheck::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($region_admin, MissingLead::class);
    }
}
