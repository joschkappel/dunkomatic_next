<?php

namespace Tests\Jobs;

use App\Enums\LeagueState;
use App\Jobs\ProcessLeagueStateChanges;

use App\Models\League;
use App\Traits\LeagueFSM;
use App\Models\Game;

use Tests\TestCase;

class ProcessLeagueStateChangesTest extends TestCase
{
    use LeagueFSM;

    private $testleague;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(4, 4)->create();
    }
    /**
     * process assigned
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function process_assigned()
    {
        // set to assigned
        $this->reopen_team_registration($this->testleague);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Registration()]);

        // disbale auto change
        $this->testleague->region->update(['auto_state_change' => false]);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Registration()]);

        // enable auto change
        $this->testleague->region->update(['auto_state_change' => true]);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Registration]);
    }
    /**
     * process registered
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function process_registered()
    {
        // set to registered
        $this->reopen_team_registration($this->testleague);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Registration]);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseMissing('leagues', ['state' => LeagueState::Registration]);
        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Selection]);
    }
    /**
     * process selected
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function process_selected()
    {
        // set to selected is done

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Selection]);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseMissing('leagues', ['state' => LeagueState::Selection]);
        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Freeze]);
    }
        /**
     * process scheduled
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function process_scheduled()
    {
        // set to selected is done
        $this->freeze_league($this->testleague);
        $this->open_game_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Scheduling]);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseMissing('leagues', ['state' => LeagueState::Scheduling]);
        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Referees]);
    }
            /**
     * process refereed
     *
     * @test
     * @group job
     * @group report
     *
     * @return void
     */
    public function process_referees()
    {
        // set to selected is done
        $this->freeze_league($this->testleague);
        $this->open_game_scheduling($this->testleague);
        $this->open_ref_assignment($this->testleague);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Referees]);
        // make sure all refs are set
        Game::whereNull('referee_1')->update(['referee_1' => '****']);

        $job_instance = resolve(ProcessLeagueStateChanges::class, ['region' => $this->testleague->region]);
        app()->call([$job_instance, 'handle']);

        $this->assertDatabaseHas('leagues', ['state' => LeagueState::Live]);

    }
}
