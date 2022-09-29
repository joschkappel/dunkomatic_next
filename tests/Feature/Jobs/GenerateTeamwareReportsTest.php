<?php

namespace Tests\Jobs;

use App\Jobs\GenerateTeamwareReport;
use App\Models\League;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateTeamwareReportsTest extends TestCase
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
     * run job generate reports
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_reports_job()
    {
        $folder = $this->testleague->region->teamware_folder;
        if (Storage::exists($folder)) {
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }

        $report_t = $folder.'/'.$this->testleague->shortname.'_Teams.csv';
        $report_g = $folder.'/'.$this->testleague->shortname.'_Games.csv';

        $job_instance = resolve(GenerateTeamwareReport::class, ['league' => $this->testleague]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report_g);
        Storage::assertExists($report_t);
    }
}
