<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;

use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;

use App\Notifications\RegisterTeams;
use App\Notifications\SelectTeamLeagueNo;

use App\Traits\LeagueFSM;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;

class LeagueStateControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->assigned(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * close assignment
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_assignment()
    {
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Assignment()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::CloseAssignment()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);

        $member = $this->testclub_assigned->members()->first();
        //  assert club members are notified
        Notification::assertSentTo(
            [$member],
            RegisterTeams::class
        );
    }

    /**
     * close registration
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_registration()
    {
        $this->close_assignment($this->testleague);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::CloseRegistration()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        $member = $this->testclub_assigned->members()->first();
        //  assert club members are notified
        Notification::assertSentTo(
            [$member],
            SelectTeamLeagueNo::class
        );
    }

    /**
     * close selection
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_selection()
    {
        $this->close_registration($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::CloseSelection()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()]);
    }

    /**
     * close freeze - generate games
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_freeze()
    {
        $this->close_selection($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to scheduling
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::CloseFreeze()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $this->testleague->id]);
    }

    /**
     * close scheduling
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_scheduling()
    {
        $this->close_freeze($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::CloseScheduling()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);
    }

    /**
     * close referee assignemnt
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function goto_referees()
    {
        $this->close_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::CloseReferees()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Live()]);
    }

    /**
     * reopen referees
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_referees()
    {
        $this->close_referees($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Live()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenReferees()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);
    }
    /**
     * reopen scheduling
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_scheduling()
    {

        $this->close_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenScheduling()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()]);
    }

    /**
     * reopen freeze
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_freeze()
    {
        $this->close_freeze($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()]);

        // change state to freeze
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenFreeze()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id]);
    }

    /**
     * reopen selection
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_selection()
    {
        $this->close_selection($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenSelection()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);
    }
    /**
     * reopen registration
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_registration()
    {
        $this->close_registration($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenRegistration()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);
    }

    /**
     * reopen assignment
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_assignment()
    {
        $this->close_assignment($this->testleague);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenAssignment()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Assignment()]);
    }

}
