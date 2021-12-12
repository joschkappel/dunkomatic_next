<?php

namespace Tests\Jobs;

use App\Models\Region;
use App\Models\Game;
use App\Enums\ReportFileType;

use App\Jobs\ProcessClubReports;
use App\Jobs\ProcessLeagueReports;

use Tests\SysTestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\PendingBatch;

use Illuminate\Support\Facades\Log;

class ProcessReportsTest extends SysTestCase
{
    /**
     * run job
     *
     * @test
     * @group job
     * @group report
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

        foreach ($clubs as $c) {
            $leagues = Game::where('club_id_home', $c->id)->with('league')->get()->pluck('league.id')->unique()->count() + 4;
            Bus::assertBatched(function (PendingBatch $batch) use ($c, $leagues) {
                // $batch->dispatch();
                return ( $batch->name == 'Club Reports ' . $c->shortname ) and ( $batch->jobs->count() == $leagues )
                       and ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     * @group report
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

        foreach ($clubs as $c) {
            $leagues = Game::where('club_id_home', $c->id)->with('league')->get()->pluck('league.id')->unique()->count() + 5;
            Bus::assertBatched(function (PendingBatch $batch) use ($c, $leagues) {
                //$batch->dispatch();
                return ( $batch->name == 'Club Reports ' . $c->shortname ) and
                    ( $batch->jobs->count() == $leagues ) and ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     * @group report
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

        foreach ($clubs as $c) {
            $leagues = Game::where('club_id_home', $c->id)->with('league')->get()->pluck('league.id')->unique()->count() * 2 + 6;
            Bus::assertBatched(function (PendingBatch $batch) use ($c, $leagues) {
                return ($batch->name == 'Club Reports ' . $c->shortname) and
                    ($batch->jobs->count() == $leagues) and ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     * @group report
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
        Storage::assertExists($region->teamware_folder);

        foreach ($leagues as $l) {
            Bus::assertBatched(function (PendingBatch $batch) use ($l) {
                return ($batch->name == 'League Reports ' . $l->shortname) &&
                    ($batch->jobs->count() == 3) && ($batch->queue() == 'exports');
            });
        }
    }

    /**
     * run job
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function run_league_2_formats_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $leagues = $region->leagues;

        $region->update(['fmt_league_reports' => ReportFileType::flags([ReportFileType::PDF, ReportFileType::CSV])]);
        $job_instance = resolve(ProcessLeagueReports::class, ['region' => $region]);
        app()->call([$job_instance, 'handle']);

        Storage::assertExists($region->league_folder);
        Storage::assertExists($region->teamware_folder);

        foreach ($leagues as $l) {
            Bus::assertBatched(function (PendingBatch $batch) use ($l) {
                return ($batch->name == 'League Reports ' . $l->shortname) &&
                    ($batch->jobs->count() == 4) && ($batch->queue() == 'exports');
            });
        }
    }

}
