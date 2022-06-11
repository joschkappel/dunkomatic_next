<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\ProcessLeagueReports;
use App\Jobs\ProcessClubReports;
use App\Jobs\ProcessNewSeason;
use App\Jobs\ProcessDbCleanup;
use App\Jobs\ProcessLeagueStateChanges;

use App\Jobs\EmailValidation;
use App\Jobs\MissingLeadCheck;
use App\Jobs\GameOverlaps;
use App\Jobs\GameNotScheduled;

use App\Models\Region;
use App\Enums\JobFrequencyType;
use App\Jobs\ProcessFilesCleanup;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     *
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ProcessDbCleanup(), 'janitor')->weeklyOn(1,'00:30')->emailOutputTo('dmatic.master@gmail.com');
        $schedule->job(new ProcessFilesCleanup(), 'janitor')->weeklyOn(1,'00:40')->emailOutputTo('dmatic.master@gmail.com');
        $schedule->job(new ProcessNewSeason(), 'janitor')->yearly();
        $schedule->command('db:backup')->dailyAt('00:10')->emailOutputTo('dmatic.master@gmail.com');
        // $schedule->exec('php artisan db:backup')->everyFifteenMinutes()->emailOutputTo('dmatic.master@gmail.com');
        $schedule->command('telescope:prune')->dailyAt('00:15')->emailOutputTo('dmatic.master@gmail.com');
        $schedule->command('authentication-log:purge')->monthlyOn(2,'00:20')->emailOutputTo('dmatic.master@gmail.com');

        // schedule region specific jobs
        $regions = Region::with('regionadmins')->get();

        foreach ($regions as $r) {
            if ($r->regionadmins()->exists()) {
                $this->scheduleRegionTask($schedule, new GameOverlaps($r), $r->job_game_overlaps);
                $this->scheduleRegionTask($schedule, new GameNotScheduled($r), $r->job_game_notime);
                $this->scheduleRegionTask($schedule, new MissingLeadCheck($r), $r->job_noleads);
                $this->scheduleRegionTask($schedule, new EmailValidation($r), $r->job_email_valid);
                $this->scheduleRegionTask($schedule, new ProcessLeagueReports($r), $r->job_league_reports);
                $this->scheduleRegionTask($schedule, new ProcessClubReports($r), $r->job_club_reports);
                // $this->scheduleRegionTask($schedule, new ProcessLeagueStateChanges($r), JobFrequencyType::daily);
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
     *
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /** schedule tasks
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @param \Illuminate\Contracts\Queue\ShouldQueue $job
     * @param  int $frequency
     * @return bool
     *
     */
    protected function scheduleRegionTask(Schedule $schedule, ShouldQueue $job, int $frequency)
    {
        // uncomment for easy testing of jobs
        // $schedule->job($job,'janitor')->everyFiveMinutes();
        // return true;
        switch ($frequency) {
            case JobFrequencyType::daily:
                $schedule->job($job, 'janitor')->daily();
                //$schedule->job($job,'janitor')->hourly();
                //$schedule->job($job,'janitor')->everyFiveMinutes();
                break;
            case JobFrequencyType::weekly:
                $schedule->job($job, 'janitor')->weekly();
                break;
            case JobFrequencyType::biweekly:
                $schedule->job($job, 'janitor')->twiceMonthly();
                break;
            case JobFrequencyType::monthly:
                $schedule->job($job, 'janitor')->monthly();
                break;
        }
        return true;
    }
}
