<?php

namespace Tests;

use Database\Seeders\SysTestDatabaseSeeder;
use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
        info('[TESTING] SysTest DB seeded');
        info('[TEST STARTING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());
    }

    public function tearDown(): void
    {
        info('[TEST STOPPING] ['.implode(' - ', $this->getGroups()).'] '.$this->getName());
        $this->artisan('migrate:fresh');
        $this->seed(TestDatabaseSeeder::class);
        info('[TESTING] Test DB seeded');

        parent::tearDown();
    }
}
