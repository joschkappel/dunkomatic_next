<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\TestDatabaseSeeder;
use Database\Seeders\SysTestDatabaseSeeder;

use Illuminate\Support\Facades\Log;

abstract class SysTestCase extends BaseTestCase
{

    use CreatesApplication;

    protected $seed = false;

    public function setUp(): void
    {
        parent::setUp();
        // seed the database

        $this->artisan('migrate:fresh');
        $this->seed(SysTestDatabaseSeeder::class);
        Log::notice('[TESTING] SysTest DB seeded');
    }

    public function tearDown(): void
    {

        $this->artisan('migrate:fresh');
        $this->seed(TestDatabaseSeeder::class);
        Log::info('[TESTING] Test DB seeded');

        parent::tearDown();

    }
}
