<?php

namespace App\Console\Commands;

use App\Enums\ReportFileType;
use App\Jobs\GenerateLeagueGamesReport;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class GenLeagueReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:league-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Region::all() as $region) {
            foreach ($region->leagues as $league) {
                Bus::batch([
                    new GenerateLeagueGamesReport($region, $league, ReportFileType::XLSX()),
                    new GenerateLeagueGamesReport($region, $league, ReportFileType::HTML())
                ])->onQueue('region_' . $region->id)
                    ->name('Leage Reports ' . $league->shortname)
                    ->onConnection('redis')
                    ->dispatch();
            };
        }
        return Command::SUCCESS;
    }
}
