<?php

namespace Tests\Feature\Jobs;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Jobs\GenerateRegionGamesReport;
use App\Models\Game;
use App\Models\League;
use App\Traits\ReportJobStatus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateRegionReportTest extends TestCase
{
    private $testleague;

    private $testclub_assigned;

    private $report_job;

    use ReportJobStatus;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->report_job = $this->job_starting($this->testleague->region, Report::RegionGames());
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
        if (Storage::exists($folder)) {
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }

        $report = $folder.'/'.$this->region->code;
        $report .= '_'.Report::RegionGames()->getReportFilename().'_v'.$this->report_job->version.'.pdf';

        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::PDF()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
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

        $report = $folder.'/'.$this->region->code;
        $report .= '_'.Report::RegionGames()->getReportFilename().'_v'.$this->report_job->version.'.xlsx';

        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::XLSX()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
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
        Game::whereNull('referee_1')->update(['referee_1' => '****']);

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $report = $folder.'/'.$this->region->code;
        $report .= '_'.Report::RegionGames()->getReportFilename().'_v'.$this->report_job->version.'.ics';

        $job_instance = resolve(GenerateRegionGamesReport::class, ['region' => $region, 'rtype' => ReportFileType::ICS()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
    }
}
