<?php

namespace Tests\Unit;

use App\Checks\LaravelEchoServerCheck;

use Tests\TestCase;
use Tests\Support\Authentication;

class LaravelEchoServerCheckTest extends TestCase
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
        if ( app()->environment('local')){

            $check = LaravelEchoServerCheck::new();

            $result = $check->run();

            $this->assertEquals('ok', $result->status->value);
            $this->assertCount(1, $result->meta);
        }

    }

}
