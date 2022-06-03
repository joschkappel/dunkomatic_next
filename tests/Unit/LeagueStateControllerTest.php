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
        $this->start_league($this->testleague);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::OpenSelection()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        $member = $this->testclub_assigned->members()->first();
        //  assert club members are notified
/*         Notification::assertSentTo(
            [$member],
            SelectTeamLeagueNo::class
        ); */
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
        $this->open_char_selection($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::FreezeLeague()
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
        $this->freeze_league($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()]);

        Notification::fake();
        Notification::assertNothingSent();

        // change state to scheduling
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague]), [
                'action' => LeagueStateChange::OpenScheduling()
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
        $this->open_game_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::OpenReferees()
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
        $this->open_ref_assignment($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);

        Notification::fake();
        Notification::assertNothingSent();


        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::GoLiveLeague()
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
        $this->golive_league($this->testleague);

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

        $this->open_ref_assignment($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Referees()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::ReOpenScheduling()
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
        $this->open_game_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()]);

        // change state to freeze
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::ReFreezeLeague()
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
        $this->freeze_league($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Freeze()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::ReOpenSelection()
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
        $this->open_char_selection($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()]);

        // change state to registration
        $response = $this->authenticated()
            ->post(route('league.state.change', ['league' => $this->testleague->id]), [
                'action' => LeagueStateChange::ReOpenRegistration()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()]);
    }



}
