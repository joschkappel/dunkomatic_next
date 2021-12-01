<?php

namespace Tests\Jobs;

use App\Jobs\MissingLeadCheck;
use App\Models\Region;
use App\Notifications\MissingLead;
use Tests\SysTestCase;
use Illuminate\Support\Facades\Notification;


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

        $region = Region::where('code','HBVDA')->first();
        $region_admin = $region->regionadmin()->first();

        $job_instance = resolve( MissingLeadCheck::class,['region'=>$region]);
        app()->call([$job_instance, 'handle']);

        Notification::assertSentTo($region_admin, MissingLead::class);
    }
}
