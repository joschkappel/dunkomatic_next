<?php

namespace Tests\Jobs;

use App\Models\Region;
use App\Models\Game;
use App\Enums\Report;
use App\Jobs\ReportProcessor;
use Tests\SysTestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportProcessorTest extends SysTestCase
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
    public function run_one_report_one_region_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $report = Report::RegionGames();

        //
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect([$report]), 'run_regions'=> collect([$region]) ]);
        app()->call([$job_instance, 'handle']);

        // Storage::assertExists($region->club_folder);
        Bus::assertBatchCount(1);
        $this->checkBatch($region, $report, 1);

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
    public function run_two_reports_one_region_job()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $report = collect([Report::RegionGames(), Report::LeagueBook()]);

        //
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>$report, 'run_regions'=> collect([$region]) ]);
        app()->call([$job_instance, 'handle']);

        // Storage::assertExists($region->club_folder);
        Bus::assertBatchCount(2);
        $this->checkBatch($region, Report::RegionGames(), 1);
        $this->checkBatch($region, Report::LeagueBook(), 1);

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
    public function trigger_reports_club()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $club = $region->clubs->first();

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $club->update(['name'=>'modified Club']);
        $this->assertDatabaseCount('audits',1);

        // run job
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertBatchCount(1);
        $this->checkBatch($club->region->parentRegion, Report::AddressBook(), 1);

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
    public function trigger_reports_league()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $league = $region->leagues->first();

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $league->update(['name'=>'modified League']);
        $this->assertDatabaseCount('audits',1);

        // run job
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(5);
        $this->checkBatch($league->region->parentRegion, Report::AddressBook(), 1);
        $this->checkBatch($league->region, Report::LeagueBook(), 1);
        $this->checkBatch($league->region, Report::RegionGames(), 1);
        $this->checkBatch($league->region, Report::ClubGames(), $league->teams->pluck('club_id')->count());
        $this->checkBatch($league->region, Report::LeagueGames(), 1);

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
    public function trigger_reports_team()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $team = $region->leagues->first()->teams->first();

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $team->update(['coach_name'=>'modified Team']);
        $this->assertDatabaseCount('audits',1);

        // run job
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(6);
        $this->checkBatch($team->league->region->parentRegion, Report::AddressBook(), 1);
        $this->checkBatch($team->league->region, Report::LeagueBook(), 1);
        $this->checkBatch($team->league->region, Report::RegionGames(), 1);
        $this->checkBatch($team->league->region, Report::ClubGames(), $team->league->teams->pluck('club_id')->count());
        $this->checkBatch($team->league->region, Report::LeagueGames(), 1);
        $this->checkBatch($team->league->region, Report::Teamware(), 1);
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
    public function trigger_reports_game()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $game = $region->leagues->first()->games->first();

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $game->update(['game_time'=>'20:30']);
        $this->assertDatabaseCount('audits',1);

        // run job
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(5);
        $this->checkBatch($game->league->region, Report::LeagueBook(), 1);
        $this->checkBatch($game->league->region, Report::RegionGames(), 1);
        $this->checkBatch($game->league->region, Report::ClubGames(), 1);
        $this->checkBatch($game->league->region, Report::LeagueGames(), 1);
        $this->checkBatch($game->league->region, Report::Teamware(), 1);
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
    public function trigger_reports_gym()
    {
        Bus::fake();
        Bus::assertNothingDispatched();

        $region = Region::where('code', 'HBVDA')->first();
        $gym = $region->clubs->first()->load('gyms')->gyms->first();
        $impacted_leagues = Game::where('gym_id',$gym->id)->pluck('league_id')->unique()->count();
        // Log::debug('impacted leagues',['cnt'=> Game::where('gym_id',$gym->id)->pluck('league_id')->unique()  ]);

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $gym->update(['name'=>'modified Gym']);
        $this->assertDatabaseCount('audits',1);

        // run job
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve( ReportProcessor::class, [ 'run_rpts'=>collect(), 'run_regions'=> collect() ]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(5);
        $this->checkBatch($gym->club->region->parentRegion, Report::AddressBook(), 1);
        $this->checkBatch($gym->club->region, Report::LeagueBook(), 1);
        $this->checkBatch($gym->club->region, Report::RegionGames(), 1);
        $this->checkBatch($gym->club->region, Report::ClubGames(), 1);
        $this->checkBatch($gym->club->region, Report::LeagueGames(), $impacted_leagues);
    }


    function checkBatch($region, $report, $jobcount){
        Bus::assertBatched(function (PendingBatch $batch) use ($region,$report, $jobcount ) {
            // $batch->dispatch();
            return ( $batch->name == 'Report Generator Jobs ' . $region->code . ' ' . $report->key ) and ( $batch->jobs->count() == $jobcount )
                    and ($batch->queue() =='region_'.$region->id);
        });
    }

}
