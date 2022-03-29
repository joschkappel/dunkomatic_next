<?php

namespace Tests\Unit;

use App\Checks\MinioHealthCheck;

use Tests\TestCase;
use Tests\Support\Authentication;

class MinioHealthCheckTest extends TestCase
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

        $check = MinioHealthCheck::new();

        $result = $check->run();

        $this->assertEquals('ok', $result->status->value);
        $this->assertCount(1, $result->meta);

    }

}
