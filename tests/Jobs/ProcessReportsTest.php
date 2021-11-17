<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Game;
use App\Enums\ReportFileType;

use App\Jobs\ProcessClubReports;
use App\Jobs\ProcessLeagueReports;

use Tests\SysTestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\PendingBatch;

class ProcessReportsTest extends SysTestCase
{
    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_club_xls_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $clubs = $region->clubs;

        // test XLSX...
        $region->update(['fmt_club_reports' => ReportFileType::flags([ReportFileType::XLSX])]);
        $job_instance = resolve(ProcessClubReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->club_folder);

        foreach ($clubs as $club) {
            Bus::assertBatched(function (PendingBatch $batch) use ($club) {
                //$batch->dispatch();
                return $batch->name == 'Club xls Reports ' . $club->first()->shortname &&
                    $batch->jobs->count() === 1 && ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_club_csv_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $clubs = $region->clubs;

        // test XLSX...
        $region->update(['fmt_club_reports' => ReportFileType::flags([ReportFileType::CSV])]);
        $job_instance = resolve(ProcessClubReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->club_folder);

        foreach ($clubs as $club) {
            Bus::assertBatched(function (PendingBatch $batch) use ($club) {
                //$batch->dispatch();
                return $batch->name == 'Club csv Reports ' . $club->first()->shortname &&
                    $batch->jobs->count() === 2 && ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_club_pdf_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $clubs = $region->clubs;

        // test XLSX...
        $region->update(['fmt_club_reports' => ReportFileType::flags([ReportFileType::PDF])]);
        $job_instance = resolve(ProcessClubReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->club_folder);

        foreach ($clubs as $club) {
            $leagues = Game::where('club_id_home', $club->id)->with('league')->get()->pluck('league.id')->unique()->count() + 3;
            Bus::assertBatched(function (PendingBatch $batch) use ($club, $leagues) {
                return ($batch->name == 'Club pdf Reports ' . $club->shortname) &&
                    ($batch->jobs->count() == $leagues) && ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_league_1_format_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $leagues = $region->leagues;

        $region->update(['fmt_league_reports' => ReportFileType::flags([ReportFileType::PDF])]);
        $job_instance = resolve(ProcessLeagueReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->league_folder);

        foreach ($leagues as $league) {
            Bus::assertBatched(function (PendingBatch $batch) use ($league) {
                return ($batch->name == 'League Reports ' . $league->shortname) &&
                    ($batch->jobs->count() == 1) && ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     *
     * @return void
     */
    public function run_league_3_formats_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $leagues = $region->leagues;

        $region->update(['fmt_league_reports' => ReportFileType::flags([ReportFileType::PDF, ReportFileType::CSV, ReportFileType::ICS])]);
        $job_instance = resolve(ProcessLeagueReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->league_folder);

        foreach ($leagues as $league) {
            Bus::assertBatched(function (PendingBatch $batch) use ($league) {
                return ($batch->name == 'League Reports ' . $league->shortname) &&
                    ($batch->jobs->count() == 3) && ($batch->queue() == 'exports');
            });
        }
    }

}
