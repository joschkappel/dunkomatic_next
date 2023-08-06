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
            GenerateRegionGamesReport::dispatch($region, ReportFileType::XLSX())->onQueue('excel')->onConnection('redis');
            GenerateRegionGamesReport::dispatch($region, ReportFileType::HTML())->onQueue('regions')->onConnection('redis');
            GenerateRegionLeaguesReport::dispatch($region, ReportFileType::XLSX())->onQueue('excel')->onConnection('redis');
            GenerateRegionLeaguesReport::dispatch($region, ReportFileType::HTML())->onQueue('regions')->onConnection('redis');
        }
        return Command::SUCCESS;
    }
}
