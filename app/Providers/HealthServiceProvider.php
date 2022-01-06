<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;


class HealthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Health::checks([
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
            RedisCheck::new(),
            PingCheck::new()->url('http://dunkomatic_next.test/healthy'),
            CpuLoadCheck::new()->failWhenLoadIsHigherInTheLast5Minutes(1.2)
        ]);
    }
}
