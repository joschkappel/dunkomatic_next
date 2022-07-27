<?php

namespace Tests\Jobs;

use App\Jobs\GenerateRegionGamesReport;
use App\Models\League;
use App\Models\Game;

use Tests\TestCase;
use App\Enums\ReportFileType;


use Illuminate\Support\Facades\Storage;

class GenerateRegionReportTest extends TestCase
{
    private $testleague;
    private $testclub_assigned;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
    }

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
        $region = $this->testleague->region;

        $folder = $region->region_folder;
        if (Storage::exists($folder)){
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }

        $report = $folder . '/' . $this->region->code;
        $report2 = $report.'_Rundenbuch.pdf';
        $report .= '_Gesamtplan.pdf';


        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::PDF()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
        Storage::assertMissing($report2);

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
        $region = $this->testleague->region;
        $folder = $region->region_folder;

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);


        $report = $folder . '/' . $this->region->code;
        $report2 = $report. '_Rundenbuch.xlsx';
        $report .= '_Gesamtplan.xlsx';


        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::XLSX()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
        Storage::assertExists($report2);

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
        $region = $this->testleague->region;
        $folder = $region->region_folder;
        // update gaems referee with $club, so we get some referee report entries
        Game::whereNull('referee_1')->update(['referee_1'=>'****']);

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $report = $folder . '/' . $this->region->code;
        $report2 = $report. '_Rundebuch.ics';
        $report .= '_Gesamtplan.ics';

        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::ICS()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
        Storage::assertMissing($report2);
    }
}
