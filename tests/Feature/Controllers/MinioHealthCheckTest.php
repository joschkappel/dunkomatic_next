<?php

namespace Tests\Feature\Controllers;

use App\Checks\MinioHealthCheck;
use Tests\Support\Authentication;
use Tests\TestCase;

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
        if (app()->environment('local')) {
            $check = MinioHealthCheck::new();

            $result = $check->run();

            $this->assertEquals('ok', $result->status->value);
            $this->assertCount(1, $result->meta);
        }
    }
}
