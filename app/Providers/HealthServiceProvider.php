<?php

namespace App\Providers;

use App\Checks\DbConnectionsCheck;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;


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
            PingCheck::new()->url('https://h2941512.stratoserver.net/healthyer'),
            CpuLoadCheck::new()
            ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
            ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
            ScheduleCheck::new(),
            DbConnectionsCheck::new()
        ]);
    }
}
