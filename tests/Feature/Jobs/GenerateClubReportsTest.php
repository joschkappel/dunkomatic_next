<?php

namespace Tests\Feature\Jobs;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Jobs\GenerateClubGamesReport;
use App\Models\Game;
use App\Models\League;
use App\Traits\ReportJobStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateClubReportsTest extends TestCase
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
        $this->report_job = $this->job_starting($this->testclub_assigned->region, Report::ClubGames());
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
        $club = $this->testclub_assigned;
        $league = $this->testleague;

        $folder = $region->club_folder;
        if (Storage::exists($folder)) {
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }

        $report = $folder.'/'.$club->shortname;
        $report .= '_'.Report::ClubGames()->getReportFilename().'_v'.$this->report_job->version.'.pdf';

        $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::PDF(),  'league' => $league]);
        app()->call([$job_instance, 'handle']);

        $files = Storage::allFiles($folder);
        Log::info($files);
        // Excel::assertStored($report);
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
        $club = $this->testclub_assigned;
        $league = $this->testleague;

        $folder = $region->club_folder;
        // Excel::fake();
        Storage::assertExists($folder);

        $report = $folder.'/'.$club->shortname;
        $report .= '_'.Report::RegionGames()->getReportFilename().'_v'.$this->report_job->version.'.xlsx';

        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::XLSX(), 'league' => $league]);
        app()->call([$job_instance, 'handle']);

        // Excel::assertStored($report);
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
        $club = $this->testclub_assigned;
        $league = $this->testleague;

        // update gaems referee with $club, so we get some referee report entries
        Game::whereNull('referee_1')->update(['referee_1' => '****']);

        $folder = $region->club_folder;
        Storage::assertExists($folder);

        $report = $folder.'/'.$club->shortname;

        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::ICS(),  'league' => $league]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report.'_'.Report::ClubGames()->getReportFilename().'_v'.$this->report_job->version.'.ics');
        Storage::assertExists($report.'_Heimspielplan_v'.$this->report_job->version.'.ics');
        Storage::assertExists($report.'_Schiriplan_v'.$this->report_job->version.'.ics');
    }
}
