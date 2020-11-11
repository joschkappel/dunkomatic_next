<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\ProcessLeagueReports;
use App\Jobs\ProcessClubReports;
use App\Jobs\ProcessNewSeason;
use App\Jobs\ProcessDbCleanup;

use App\Jobs\EmailValidation;
use App\Jobs\MissingLeadCheck;
use App\Jobs\GameOverlaps;
use App\Jobs\GameNotScheduled;

use App\Models\Region;
use App\Models\User;
use App\Enums\JobFrequencyType;

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
        $schedule->job(new ProcessDbCleanup(), 'janitor')->weekly();//everyFiveMinutes()
        $schedule->job(new ProcessNewSeason(),'janitor')->yearly();
        $schedule->command('telescope:prune')->daily();

        // schedule region specific jobs
        $regions = Region::all();

        foreach ($regions as $r){
          if (User::regionAdmin($r->code)->exists()){
              $this->scheduleRegionTask($schedule, new GameOverlaps($r), $r->job_game_overlaps);
              $this->scheduleRegionTask($schedule, new GameNotScheduled($r), $r->job_game_notime);
              $this->scheduleRegionTask($schedule, new MissingLeadCheck($r), $r->job_noleads);
              $this->scheduleRegionTask($schedule, new EmailValidation($r), $r->job_email_valid);
              $this->scheduleRegionTask($schedule, new ProcessLeagueReports($r), $r->job_league_reports);
              $this->scheduleRegionTask($schedule, new ProcessClubReports($r), $r->job_club_reports);
          }
        }
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
    *
    */
    protected function scheduleRegionTask($schedule, $job, $frequency)
    {
      // uncomment for easy testing of jobs
      // $schedule->job($job,'janitor')->everyFiveMinutes();
      // return true;
        switch ($frequency) {
          case JobFrequencyType::daily :
            //$schedule->job($job,'janitor')->daily();
            $schedule->job($job,'janitor')->hourly();
            break;
          case JobFrequencyType::weekly :
            $schedule->job($job,'janitor')->weekly();
            //$schedule->job($job,'janitor')->everyThreeMinutes();
            break;
          case JobFrequencyType::biweekly :
            $schedule->job($job,'janitor')->twiceMonthly();
            //$schedule->job($job,'janitor')->everyFourMinutes();
            break;
          case JobFrequencyType::monthly :
            $schedule->job($job,'janitor')->monthly();
            //$schedule->job($job,'janitor')->everyFiveMinutes();
            break;
          }
      return true;
    }

}
