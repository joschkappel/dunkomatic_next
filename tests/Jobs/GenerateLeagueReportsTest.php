<?php

namespace Tests\Unit;

use App\Jobs\GenerateLeagueGamesReport;
use App\Models\Region;
use App\Models\Game;

use Tests\SysTestCase;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class GenerateLeagueReportsTest extends SysTestCase
{


    /**
     * run job generate pdf repoerst
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_pdf_reports_job()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $league = Game::first()->league;

        $folder = $region->league_folder;
        // Excel::fake();
        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $report = $folder . '/' . $league->shortname;
        $report .= '_games.pdf';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $league, 'rtype' => ReportFileType::PDF()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
        // Excel::assertStored($report);
    }

    /**
     * run job generate xlsx repoerst
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_xlsx_reports_job()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $league = Game::first()->league;

        $folder = $region->league_folder;
//        Excel::fake();
        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);


        $report = $folder . '/' . $league->shortname;
        $report .= '_games.xlsx';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $league, 'rtype' => ReportFileType::XLSX()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
//        Excel::assertStored($report);
    }

    /**
     * run job generate ics repoerst
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_ics_reports_job()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $league = Game::first()->league;

        $folder = $region->league_folder;

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $report = $folder . '/' . $league->shortname;
        $report .= '_games.ics';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $league, 'rtype' => ReportFileType::ICS()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
    }
}