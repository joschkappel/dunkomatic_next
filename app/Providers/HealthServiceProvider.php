<?php

namespace App\Providers;

use App\Checks\BroadcastingCheck;
use App\Checks\DbConnectionsCheck;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;


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
        if (config('app.env') == 'prod'){
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url('https://h2941512.stratoserver.net/healthy'),
                // ScheduleCheck::new(),
                EnvironmentCheck::new()->expectEnvironment('prod'),
                CacheCheck::new(),
                DebugModeCheck::new(),
                BroadcastingCheck::new()
            ]);
        } elseif (config('app.env') == 'local'){
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(2.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(1.5),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                EnvironmentCheck::new()->expectEnvironment('local'),
                CacheCheck::new(),
                BroadcastingCheck::new()
            ]);
        } else {
            // do nothing;
        }
    }
}
