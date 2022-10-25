<?php

namespace Tests\Feature\Controllers;

use App\Checks\FailedLoginsCheck;
use Tests\Support\Authentication;
use Tests\TestCase;

class FailedLoginsCheckTest extends TestCase
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
        $check = FailedLoginsCheck::new()
                    ->failWhenFailedLoginsIsHigherInTheLastMinute(1)
                    ->failWhenFailedLoginsIsHigherInTheLast5Minutes(2)
                    ->failWhenFailedLoginsIsHigherInTheLast15Minutes(4);

        $result = $check->run();

        $this->assertEquals('ok', $result->status->value);
        $this->assertCount(3, $result->meta);
    }
}
