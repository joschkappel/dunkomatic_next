<?php

namespace Tests\Jobs;

use App\Enums\Report;
use App\Jobs\GenerateTeamwareReport;
use App\Models\League;
use App\Traits\ReportJobStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class GenerateTeamwareReportsTest extends TestCase
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
        $this->report_job = $this->job_starting($this->testleague->region, Report::Teamware());
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

        $report_t = $folder.'/'.Str::slug($this->testleague->shortname, '_').'_teams_v'.$this->report_job->version.'.csv';
        $report_g = $folder.'/'.Str::slug($this->testleague->shortname, '_').'_games_v'.$this->report_job->version.'.csv';

        $job_instance = resolve(GenerateTeamwareReport::class, ['league' => $this->testleague]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($report_g);
        Storage::assertExists($report_t);
    }
}
