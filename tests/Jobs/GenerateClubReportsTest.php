<?php

namespace Tests\Jobs;

use App\Jobs\GenerateClubGamesReport;
use App\Models\Region;
use App\Models\Game;
use Tests\SysTestCase;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;

use Illuminate\Support\Facades\Storage;

class GenerateClubReportsTest extends SysTestCase
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
        $club = $region->clubs->first();
        $league = Game::where('club_id_home',$club->id)->first()->league;

        $folder = $region->club_folder;
        if (Storage::disk('exports')->exists($folder)){
            Storage::disk('exports')->assertExists($folder);
            $files = Storage::disk('exports')->allFiles($folder);
            Storage::disk('exports')->delete($files);
        }


        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_games.pdf';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_games_all.pdf';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_games_home.pdf';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_games_referee.pdf';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_games.pdf';
                    break;
            }

            $files = Storage::disk('exports')->allFiles($folder);
            Storage::disk('exports')->delete($files);

            $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::PDF(), 'scope' => $rscope, 'league'=>$league]);
            app()->call([$job_instance, 'handle']);

            // Excel::assertStored($report);
            Storage::disk('exports')->assertExists($report);
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
        $region = Region::where('code', 'HBVDA')->first();
        $club = $region->clubs->first();
        $league = Game::where('club_id_home',$club->id)->first()->league;

        $folder = $region->club_folder;
        // Excel::fake();
        Storage::disk('exports')->assertExists($folder);

        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_games.xlsx';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_games_all.xlsx';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_games_home.xlsx';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_games_referee.xlsx';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_games.xlsx';
                    break;
            }

            $files = Storage::disk('exports')->allFiles($folder);
            Storage::disk('exports')->delete($files);

            $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::XLSX(), 'scope' => $rscope, 'league'=>$league]);
            app()->call([$job_instance, 'handle']);

            // Excel::assertStored($report);
            Storage::disk('exports')->assertExists($report);
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
        $region = Region::where('code', 'HBVDA')->first();
        $club = $region->clubs->first();
        Game::where('club_id_home',$club->id)->update(['referee_1'=>'****']);
        $league = Game::where('club_id_home',$club->id)->first()->league;

        $folder = $region->club_folder;
        Storage::disk('exports')->assertExists($folder);

        foreach (ReportScope::getInstances() as $rscope) {
            $report = $folder . '/' . $club->shortname;
            switch ($rscope->value) {
                case ReportScope::ms_all:
                    $report .= '_games.ics';
                    break;
                case ReportScope::ss_club_all:
                    $report .= '_games_all.ics';
                    break;
                case ReportScope::ss_club_home:
                    $report .= '_games_home.ics';
                    break;
                case ReportScope::ss_club_referee:
                    $report .= '_games_referee.ics';
                    break;
                case ReportScope::ss_club_league:
                    $report .= '_' . $league->shortname . '_games.ics';
                    break;
            }

            if ($rscope->value != ReportScope::ms_all ){
                $files = Storage::disk('exports')->allFiles($folder);
                Storage::disk('exports')->delete($files);

                $job_instance = resolve(GenerateClubGamesReport::class, ['region' => $region, 'club' => $club, 'rtype' => ReportFileType::ICS(), 'scope' => $rscope, 'league'=>$league]);
                app()->call([$job_instance, 'handle']);

                Storage::disk('exports')->assertExists($report);
            }
        }
    }
}
