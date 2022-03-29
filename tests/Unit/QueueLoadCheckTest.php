<?php

namespace Tests\Unit;

use App\Checks\QueueLoadCheck;

use Tests\TestCase;
use Tests\Support\Authentication;

class QueueLoadCheckTest extends TestCase
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

        $check = QueueLoadCheck::new()
                    ->failWhenFailedJobsIsHigher(5)
                    ->failWhenQueueLengthIsHigher(10);

        $result = $check->run();

        $this->assertEquals('ok', $result->status->value);
        $this->assertCount(7, $result->meta);

    }

}
