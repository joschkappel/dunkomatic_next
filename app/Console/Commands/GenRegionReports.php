<?php

namespace App\Console\Commands;

use App\Enums\ReportFileType;
use App\Jobs\GenerateRegionGamesReport;
use App\Jobs\GenerateRegionLeaguesReport;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class GenRegionReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:region-reports';

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
            Bus::batch([
                new GenerateRegionGamesReport($region, ReportFileType::XLSX()),
                new GenerateRegionGamesReport($region, ReportFileType::HTML()),
                new GenerateRegionLeaguesReport($region, ReportFileType::XLSX()),
                new GenerateRegionLeaguesReport($region, ReportFileType::HTML())
            ])->onQueue('region_' . $region->id)
                ->name('Region Reports ' . $region->code)
                ->onConnection('redis')
                ->dispatch();
        }
        return Command::SUCCESS;
    }
}
