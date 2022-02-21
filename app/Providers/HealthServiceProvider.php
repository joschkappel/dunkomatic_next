<?php

namespace App\Providers;

use App\Checks\LaravelEchoServerCheck;
use App\Checks\ConcurrentUsersCheck;
use App\Checks\DbConnectionsCheck;
use App\Checks\MinioHealthCheck;
use App\Checks\QueueLoadCheck;
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
                ->failWhenLoadIsHigherInTheLast5Minutes(10.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5.5),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url( env('APP_URL') .'/healthy'),
                // ScheduleCheck::new(),
                EnvironmentCheck::new()->expectEnvironment('prod'),
                CacheCheck::new(),
                DebugModeCheck::new(),
                LaravelEchoServerCheck::new(),
                ConcurrentUsersCheck::new()
                ->failWhenFailedLoginsIsHigherInTheLastMinute(80)
                ->failWhenFailedLoginsIsHigherInTheLast5Minutes(50)
                ->failWhenFailedLoginsIsHigherInTheLast15Minutes(30),
                QueueLoadCheck::new()
                ->failWhenFailedJobsIsHigher(5)
                ->failWhenQueueLengthIsHigher(10),
                MinioHealthCheck::new(),
            ]);
        } elseif (config('app.env') == 'local'){
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(10.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5.0),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url( 'http://nginx/healthy'),
                EnvironmentCheck::new()->expectEnvironment('local'),
                CacheCheck::new(),
                LaravelEchoServerCheck::new(),
                ConcurrentUsersCheck::new()
                ->failWhenFailedLoginsIsHigherInTheLastMinute(80)
                ->failWhenFailedLoginsIsHigherInTheLast5Minutes(50)
                ->failWhenFailedLoginsIsHigherInTheLast15Minutes(30),
                QueueLoadCheck::new()
                ->failWhenFailedJobsIsHigher(5)
                ->failWhenQueueLengthIsHigher(10),
                MinioHealthCheck::new(),
            ]);
        } else {
            // do nothing;
        }
    }
}
