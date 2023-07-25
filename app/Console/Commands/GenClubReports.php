<?php

namespace App\Console\Commands;

use App\Enums\ReportFileType;
use App\Jobs\GenerateClubGamesReport;
use App\Models\Club;
use App\Models\Region;
use Illuminate\Console\Command;
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
        dispatch(new GenerateClubGamesReport(Region::find(2), Club::find(58), ReportFileType::XLSX()))->onQueue('region_2');
        return Command::SUCCESS;
    }
}
