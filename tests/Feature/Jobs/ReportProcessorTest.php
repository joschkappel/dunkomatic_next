<?php

namespace Tests\Feature\Jobs;

use App\Enums\LeagueState;
use App\Enums\Report;
use App\Jobs\ReportProcessor;
use App\Models\Club;
use App\Models\Game;
use App\Models\League;
use App\Models\Region;
use App\Models\Team;
use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\SysTestCase;

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
        // se leagues to schedulng
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        //
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect([$report]), 'run_regions' => collect([$region])]);
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
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        //
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => $report, 'run_regions' => collect([$region])]);
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
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        $clubs = $region->clubs->pluck('id');
        $leagues = $region->leagues()->where('state', LeagueState::Scheduling)->pluck('id');
        $club = Club::find(Game::whereIn('club_id_home', $clubs)->whereIn('league_id', $leagues)->first()->club_id_home);
        Log::info('[EXPECT] run reports for ', ['club-id' => $club->id]);

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $club->update(['name' => 'modified Club']);
        $this->assertDatabaseCount('audits', 1);

        // run job
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
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
        League::where('state', LeagueState::Freeze)->update(['state' => LeagueState::Scheduling]);

        $leagues = Game::whereNotNull('club_id_home')->pluck('league_id');
        $league = League::find(Game::whereIn('league_id', $leagues)->first()->league_id);

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $league->update(['name' => 'modified League']);
        $this->assertDatabaseCount('audits', 1);

        // run job
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
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
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        $clubs = $region->clubs->pluck('id');
        $leagues = $region->leagues()->where('state', LeagueState::Scheduling)->pluck('id');
        $team = Team::find(Game::whereIn('club_id_home', $clubs)->whereIn('league_id', $leagues)->first()->team_id_home);

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $team->update(['team_no' => '10']);
        $this->assertDatabaseCount('audits', 1);

        // run job
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(6);
        $this->checkBatch($team->league->region->parentRegion, Report::AddressBook(), 1);
        $this->checkBatch($team->league->region, Report::LeagueBook(), 1);
        $this->checkBatch($team->league->region, Report::RegionGames(), 1);
        $this->checkBatch($team->league->region, Report::ClubGames(), $team->league->teams->pluck('club_id')->count());
        $this->checkBatch($team->league->region, Report::LeagueGames(), 1);
        $this->checkBatch($team->league->region, Report::Teamware(), 41);
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
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        $clubs = $region->clubs->pluck('id');
        $leagues = $region->leagues()->where('state', LeagueState::Scheduling)->pluck('id');
        $game = Game::whereIn('club_id_home', $clubs)->whereIn('league_id', $leagues)->first();

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $game->update(['game_time' => '20:30']);
        $this->assertDatabaseCount('audits', 1);

        // run job
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(5);
        $this->checkBatch($game->league()->first()->region, Report::LeagueBook(), 1);
        $this->checkBatch($game->league()->first()->region, Report::RegionGames(), 1);
        $this->checkBatch($game->league()->first()->region, Report::ClubGames(), 1);
        $this->checkBatch($game->league()->first()->region, Report::LeagueGames(), 1);
        $this->checkBatch($game->league()->first()->region, Report::Teamware(), 41);
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
        League::whereNot('state', LeagueState::Scheduling)->update(['state' => LeagueState::Scheduling]);

        $clubs = $region->clubs->pluck('id');
        $leagues = $region->leagues()->where('state', LeagueState::Scheduling)->pluck('id');
        $club = Club::find(Game::whereIn('club_id_home', $clubs)->whereIn('league_id', $leagues)->first()->club_id_home);

        $gym = $club->load('gyms')->gyms->first();
        $impacted_leagues = Game::where('gym_id', $gym->id)->pluck('league_id')->unique()->count();
        // Log::debug('impacted leagues',['cnt'=> Game::where('gym_id',$gym->id)->pluck('league_id')->unique()  ]);

        // remove audits
        DB::table('audits')->truncate();
        // modify club
        $gym->update(['name' => 'modified Gym']);
        $this->assertDatabaseCount('audits', 1);

        // run job
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);
        Bus::assertNothingDispatched();

        // move to tomorrow
        $this->travel(1)->days();
        $job_instance = resolve(ReportProcessor::class, ['run_rpts' => collect(), 'run_regions' => collect()]);
        app()->call([$job_instance, 'handle']);

        Bus::assertBatchCount(5);
        $this->checkBatch($gym->club->region->parentRegion, Report::AddressBook(), 1);
        $this->checkBatch($gym->club->region, Report::LeagueBook(), 1);
        $this->checkBatch($gym->club->region, Report::RegionGames(), 1);
        $this->checkBatch($gym->club->region, Report::ClubGames(), 1);
        $this->checkBatch($gym->club->region, Report::LeagueGames(), $impacted_leagues);
    }

    public function checkBatch($region, $report, $jobcount)
    {
        Bus::assertBatched(function (PendingBatch $batch) use ($region, $report, $jobcount) {
            // $batch->dispatch();
            return ($batch->name == 'Report Generator Jobs '.$region->code.' '.$report->key) and ($batch->jobs->count() == $jobcount)
                    and ($batch->queue() == 'region_'.$region->id);
        });
    }
}
