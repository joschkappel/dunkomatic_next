<?php

namespace Tests\Feature;

use App\Checks\ConcurrentUsersCheck;
use Tests\Support\Authentication;
use Tests\TestCase;

class ConcurrentUsersCheckTest extends TestCase
{
    use Authentication;

    /**
     * health_check
     *
     * @test
     * @group check
     *
     * @return void
     */
    public function health_check()
    {
        $check = ConcurrentUsersCheck::new()
                    ->failWhenFailedLoginsIsHigherInTheLastMinute(80)
                    ->failWhenFailedLoginsIsHigherInTheLast5Minutes(50)
                    ->failWhenFailedLoginsIsHigherInTheLast15Minutes(30);

        $result = $check->run();

        $this->assertEquals('ok', $result->status->value);
        $this->assertCount(6, $result->meta);
    }
}
