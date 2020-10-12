<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\ProcessLeagueReports;
use App\Jobs\ProcessNewSeason;
use App\Jobs\DailyJanitor;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ProcessLeagueReports('HBV'), 'exports')->daily();
        //$schedule->job(new ProcessLeagueReports('HBV'), 'exports')->everyMinute();
        $schedule->job(new ProcessLeagueReports('HBVDA'), 'exports')->daily();
        $schedule->job(new DailyJanitor(), 'janitor')->daily();
        $schedule->job(new ProcessNewSeason(),'janitor')->yearly();

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
}
