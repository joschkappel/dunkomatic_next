<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\TestDatabaseSeeder;
use Database\Seeders\SysTestDatabaseSeeder;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

abstract class SysTestCase extends BaseTestCase
{

    use CreatesApplication, RefreshDatabase;

    protected $seed = false;

    public function setUp(): void
    {
        parent::setUp();
        // seed the database

        Artisan::call('migrate:fresh');
        $this->seed(SysTestDatabaseSeeder::class);
        Log::info('TestDB seeded');
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:fresh');
        $this->seed(TestDatabaseSeeder::class);

        parent::tearDown();
        //
    }
}
