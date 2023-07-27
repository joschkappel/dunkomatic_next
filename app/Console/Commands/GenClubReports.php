<?php

namespace App\Console\Commands;

use App\Enums\ReportFileType;
use App\Jobs\GenerateClubGamesReport;
use App\Models\Club;
use App\Models\Region;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;

class GenClubReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:club-reports';

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
            foreach ($region->clubs()->active()->get() as $club) {
                Bus::batch([
                    new GenerateClubGamesReport($region, $club, ReportFileType::XLSX()),
                    new GenerateClubGamesReport($region, $club, ReportFileType::HTML())
                ])->onQueue('region_' . $region->id)
                    ->name('Club Reports ' . $club->shortname)
                    ->onConnection('redis')
                    ->dispatch();
            };
        }
        return Command::SUCCESS;
    }
}
