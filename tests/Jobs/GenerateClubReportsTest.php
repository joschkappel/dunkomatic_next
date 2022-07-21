<?php

namespace Tests\Jobs;

use App\Jobs\GenerateClubGamesReport;
use App\Models\League;
use App\Models\Game;

use Tests\TestCase;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Illuminate\Support\Facades\Storage;

class GenerateClubReportsTest extends TestCase
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
        $club = $this->testclub_assigned;
        $league = $this->testleague;

        $folder = $region->club_folder;
        if (Storage::exists($folder)){
            Storage::assertExists($folder);
            $files = Storage::allFiles($folder);
            Storage::delete($files);
        }


        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_Gesamtplan.pdf';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_Vereinsplan.pdf';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_Heimspielplan.pdf';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_Schiriplan.pdf';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_Rundenplan.pdf';
                    break;
            }

            $files = Storage::allFiles($folder);
            Storage::delete($files);

            $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::PDF(), 'scope' => $rscope, 'league'=>$league]);
            app()->call([$job_instance, 'handle']);

            // Excel::assertStored($report);
            Storage::assertExists($report);
        }
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

        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_Gesamtplan.xlsx';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_Vereinsplan.xlsx';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_Heimspielplan.xlsx';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_Schiriplan.xlsx';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_Rundenplan.xlsx';
                    break;
            }

            $files = Storage::allFiles($folder);
            Storage::delete($files);

            $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::XLSX(), 'scope' => $rscope, 'league'=>$league]);
            app()->call([$job_instance, 'handle']);

            // Excel::assertStored($report);
            Storage::assertExists($report);
        }
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
        Game::whereNull('referee_1')->update(['referee_1'=>'****']);

        $folder = $region->club_folder;
        Storage::assertExists($folder);

        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_Gesamtplan.ics';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_Vereinsplan.ics';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_Heimspielplan.ics';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_Schiriplan.ics';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_Rundenplan.ics';
                    break;
            }

            if ($rscope->value != ReportScope::ms_all ){
                $files = Storage::allFiles($folder);
                Storage::delete($files);

                $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::ICS(), 'scope' => $rscope, 'league'=>$league]);
                app()->call([$job_instance, 'handle']);

                Storage::assertExists($report);
            }
        }
    }
}
