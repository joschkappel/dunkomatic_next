<?php
namespace Tests\Support;

use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Support\Facades\Log;

trait MigrateFreshSeedOnce
{
    /**
    * If true, setup has run at least once.
    * @var boolean
    */
    protected static $setUpHasRunOnce = false;
    /**
    * After the first run of setUp "migrate:fresh --seed"
    * @return void
    */
    public function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpHasRunOnce) {
            $this->artisan('migrate:fresh');
            $this->seed(TestDatabaseSeeder::class);
            Log::info('TestDB reset');
            static::$setUpHasRunOnce = true;
         }
    }
}
