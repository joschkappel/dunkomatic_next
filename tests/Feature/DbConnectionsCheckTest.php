<?php

namespace Tests\Feature;

use App\Checks\DbConnectionsCheck;
use Tests\Support\Authentication;
use Tests\TestCase;

class DbConnectionsCheckTest extends TestCase
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
        $check = DbConnectionsCheck::new();

        $result = $check->run();

        $this->assertEquals('ok', $result->status->value);
        $this->assertCount(1, $result->meta);
    }
}
