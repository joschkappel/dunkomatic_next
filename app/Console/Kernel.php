<?php

namespace App\Console;

use App\Enums\JobFrequencyType;
use App\Jobs\CloseLeagueState;
use App\Jobs\EmailValidation;
use App\Jobs\GameNotScheduled;
use App\Jobs\GameOverlaps;
use App\Jobs\MissingLeadCheck;
use App\Jobs\OpenLeagueState;
use App\Jobs\ProcessCustomMessages;
use App\Jobs\ProcessDbCleanup;
use App\Jobs\ProcessFilesCleanup;
use App\Jobs\ProcessNewSeason;
use App\Jobs\ReportProcessor;
use App\Models\Region;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:backup -c')->daily()->emailOutputOnFailure('dmatic.master@gmail.com');
        $schedule->command('telescope:prune')->dailyAt('00:10')->environments(['staging', 'local', 'dev']);
        $schedule->job(new ProcessDbCleanup(), 'janitor')->weeklyOn(1, '00:15');
        $schedule->job(new ProcessFilesCleanup(), 'janitor')->weeklyOn(1, '00:20');
        $schedule->job(new ProcessCustomMessages(), 'janitor')->dailyAt('03:00');
        $schedule->job(new ProcessNewSeason(), 'janitor')->yearly();
        // $schedule->job(new ExportStatistics(), 'janitor')->everyMinute();
        $schedule->job(new OpenLeagueState(), 'janitor')->dailyAt('07:45');
        $schedule->job(new CloseLeagueState(), 'janitor')->dailyAt('20:00');
        $schedule->job(new ReportProcessor(collect(), collect()), 'janitor')->dailyAt('00:10');

        // schedule region specific jobs
        $regions = Region::with('regionadmins')->get();

        foreach ($regions as $region) {
            if ($region->regionadmins()->exists()) {
                $this->scheduleRegionTask($region, $schedule, new GameOverlaps($region), $region->job_game_overlaps, '00:01');
                $this->scheduleRegionTask($region, $schedule, new GameNotScheduled($region), $region->job_game_notime, '00:02');
                $this->scheduleRegionTask($region, $schedule, new MissingLeadCheck($region), $region->job_noleads, '00:03');
                $this->scheduleRegionTask($region, $schedule, new EmailValidation($region), $region->job_email_valid, '00:04');
            }
        }

        if (env('HEALTH_FREQUENCY', 15) == 1) {
            $schedule->command(RunHealthChecksCommand::class)->everyMinute();
        } elseif (env('HEALTH_FREQUENCY', 15) == 2) {
            $schedule->command(RunHealthChecksCommand::class)->everyTwoMinutes();
        } elseif (env('HEALTH_FREQUENCY', 15) == 5) {
            $schedule->command(RunHealthChecksCommand::class)->everyFiveMinutes();
        } elseif (env('HEALTH_FREQUENCY', 15) == 10) {
            $schedule->command(RunHealthChecksCommand::class)->everyTenMinutes();
        } else {
            $schedule->command(RunHealthChecksCommand::class)->everyFifteenMinutes();
        }

        // $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /** schedule tasks
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @param  \Illuminate\Contracts\Queue\ShouldQueue  $job
     * @param  int  $frequency
     * @return bool
     */
    protected function scheduleRegionTask(Region $region, Schedule $schedule, ShouldQueue $job, int $frequency, string $startAt)
    {
        // uncomment for easy testing of jobs
        // $schedule->job($job,'janitor')->everyFiveMinutes();
        // return true;
        switch ($frequency) {
            case JobFrequencyType::daily:
                $schedule->job($job, 'region_'.$region->id, 'redis')->dailyAt($startAt);
                //$schedule->job($job,'janitor')->hourly();
                //$schedule->job($job,'janitor')->everyFiveMinutes();
                break;
            case JobFrequencyType::weekly:
                $schedule->job($job, 'region_'.$region->id, 'redis')->weeklyOn(1, $startAt);
                break;
            case JobFrequencyType::biweekly:
                $schedule->job($job, 'region_'.$region->id, 'redis')->twiceMonthlyOn(1, 16, $startAt);
                break;
            case JobFrequencyType::monthly:
                $schedule->job($job, 'region_'.$region->id, 'redis')->monthlyOn(1, $startAt);
                break;
        }

        return true;
    }
}
