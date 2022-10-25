<?php

namespace App\Providers;

use App\Checks\DbConnectionsCheck;
use App\Checks\DuplicateMemberCheck;
use App\Checks\FailedLoginsCheck;
use App\Checks\LaravelEchoServerCheck;
use App\Checks\MinioHealthCheck;
use App\Checks\QueueLoadCheck;
use Illuminate\Support\ServiceProvider;
use Spatie\CpuLoadHealthCheck\CpuLoadCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\PingCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Facades\Health;

class HealthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $checkurl = config('app.url');

        if (app()->environment('prod')) {
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(10.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5.5),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url($checkurl.'/healthy'),
                // ScheduleCheck::new(),
                EnvironmentCheck::new()->expectEnvironment('prod'),
                CacheCheck::new(),
                DebugModeCheck::new(),
                LaravelEchoServerCheck::new(),
                FailedLoginsCheck::new()
                ->failWhenFailedLoginsIsHigherInTheLastMinute(5)
                ->failWhenFailedLoginsIsHigherInTheLast5Minutes(10)
                ->failWhenFailedLoginsIsHigherInTheLast15Minutes(30),
                QueueLoadCheck::new()
                ->failWhenFailedJobsIsHigher(5)
                ->failWhenQueueLengthIsHigher(10),
                MinioHealthCheck::new(),
                DuplicateMemberCheck::new()
                ->failWhenDuplicatesIsHigher(50),
            ]);
        } elseif (app()->environment('staging')) {
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(10.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5.5),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url($checkurl.'/healthy'),
                // ScheduleCheck::new(),
                EnvironmentCheck::new()->expectEnvironment('staging'),
                CacheCheck::new(),
                LaravelEchoServerCheck::new(),
                FailedLoginsCheck::new()
                ->failWhenFailedLoginsIsHigherInTheLastMinute(5)
                ->failWhenFailedLoginsIsHigherInTheLast5Minutes(10)
                ->failWhenFailedLoginsIsHigherInTheLast15Minutes(20),
                QueueLoadCheck::new()
                ->failWhenFailedJobsIsHigher(5)
                ->failWhenQueueLengthIsHigher(10),
                MinioHealthCheck::new(),
                DuplicateMemberCheck::new()
                ->failWhenDuplicatesIsHigher(10),
            ]);
        } elseif (app()->environment('local')) {
            Health::checks([
                UsedDiskSpaceCheck::new(),
                CpuLoadCheck::new()
                ->failWhenLoadIsHigherInTheLast5Minutes(10.0)
                ->failWhenLoadIsHigherInTheLast15Minutes(5.0),
                DbConnectionsCheck::new(),
                DatabaseCheck::new(),
                RedisCheck::new(),
                PingCheck::new()->url('http://nginx/healthy'),
                EnvironmentCheck::new()->expectEnvironment('local'),
                CacheCheck::new(),
                LaravelEchoServerCheck::new(),
                FailedLoginsCheck::new()
                ->failWhenFailedLoginsIsHigherInTheLastMinute(1)
                ->failWhenFailedLoginsIsHigherInTheLast5Minutes(2)
                ->failWhenFailedLoginsIsHigherInTheLast15Minutes(5),
                QueueLoadCheck::new()
                ->failWhenFailedJobsIsHigher(5)
                ->failWhenQueueLengthIsHigher(10),
                MinioHealthCheck::new(),
                DuplicateMemberCheck::new()
                ->failWhenDuplicatesIsHigher(50),
            ]);
        } else {
            // do nothing;
        }
    }
}
