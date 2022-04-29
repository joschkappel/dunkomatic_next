<?php

namespace Tests\Jobs;

use App\Jobs\GenerateLeagueGamesReport;
use App\Models\League;
use App\Models\Game;

use Tests\TestCase;
use App\Enums\ReportFileType;


use Illuminate\Support\Facades\Storage;

class GenerateLeagueReportsTest extends TestCase
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

        $folder = $region->league_folder;
        if (Storage::exists($folder)){
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }

        $report = $folder . '/' . $this->testleague->shortname;
        $report .= '_games.pdf';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $this->testleague, 'rtype' => ReportFileType::PDF()]);
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
        $folder = $region->league_folder;

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);


        $report = $folder . '/' . $this->testleague->shortname;
        $report .= '_games.xlsx';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $this->testleague, 'rtype' => ReportFileType::XLSX()]);
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
        $folder = $region->league_folder;
        // update gaems referee with $club, so we get some referee report entries
        Game::whereNull('referee_1')->update(['referee_1'=>'****']);

        Storage::assertExists($folder);
        $files = Storage::allFiles($folder);
        Storage::delete($files);

        $report = $folder . '/' . $this->testleague->shortname;
        $report .= '_games.ics';

        $job_instance = resolve(GenerateLeagueGamesReport::class, ['region' => $region, 'league' => $this->testleague, 'rtype' => ReportFileType::ICS()]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report);
    }
}
