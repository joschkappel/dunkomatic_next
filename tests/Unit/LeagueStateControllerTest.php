<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;
use App\Models\Member;
use App\Models\Game;
use App\Models\Team;
use App\Models\Gym;
use App\Models\Schedule;
use Illuminate\Support\Carbon;

use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;
use App\Enums\Role;

use App\Notifications\RegisterTeams;
use App\Notifications\SelectTeamLeagueNo;
use App\Notifications\LeagueGamesGenerated;
use App\Traits\LeagueFSM;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;

class LeagueStateControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

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

        $this->open_registration(static::$testleague);
        $this->open_assignment(static::$testleague);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague]), [
                'action' => LeagueStateChange::CloseAssignment()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()]);

        $member = static::$testclub->members()->first();
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
        $this->open_registration(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague]), [
                'action' => LeagueStateChange::CloseRegistration()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Selection()]);

        $member = static::$testclub->members()->first();
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
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague]), [
                'action' => LeagueStateChange::CloseSelection()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Freeze()]);
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
        $this->close_selection(static::$testleague);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Freeze()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to scheduling
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague]), [
                'action' => LeagueStateChange::CloseFreeze()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => static::$testleague->id]);
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
        $this->close_selection(static::$testleague);
        $this->close_freeze(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::CloseScheduling()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Referees()]);
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

        $this->close_selection(static::$testleague);
        $this->close_freeze(static::$testleague);
        $this->close_scheduling(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Referees()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::CloseReferees()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Live()]);
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
        $this->close_selection(static::$testleague);
        $this->close_freeze(static::$testleague);
        $this->close_scheduling(static::$testleague);
        $this->close_referees(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Live()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenReferees()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Referees()]);
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
        $this->close_selection(static::$testleague);
        $this->close_freeze(static::$testleague);
        $this->close_scheduling(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Referees()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenScheduling()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()]);
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
        $this->close_selection(static::$testleague);
        $this->close_freeze(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()]);

        // change state to freeze
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenFreeze()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Freeze()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id]);
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
        $this->close_selection(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Freeze()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenSelection()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Selection()]);
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
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenRegistration()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()]);
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
        $this->open_registration(static::$testleague);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => static::$testleague->id]), [
                'action' => LeagueStateChange::OpenAssignment()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()]);
    }

}
