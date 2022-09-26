<?php

namespace Tests\Feature;

use App\Enums\LeagueState;
use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueRegistrationInjectTeamTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * Assign club
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function assign_club()
    {
        $c_toadd = $this->testclub_free;

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);

        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague->id]),
                ['club_id' => $c_toadd->id, 'item_id' => $this->testleague->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * Register team
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function register_team()
    {
        $c_toadd = $this->testclub_free;

        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague->id]),
                ['club_id' => $c_toadd->id, 'item_id' => $this->testleague->id]
            );

        // now register the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->put(
                route('league.register.team', ['league' => $this->testleague->id, 'team' => $c_toadd->teams->first()->id])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(4, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * withdraw team
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function withdraw_team()
    {
        // now withdraw a team
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.team.withdraw', ['league' => $this->testleague->id]),
                ['team_id' => $this->testclub_assigned->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(2, $this->testleague->state_count['assigned']);
        $this->assertEquals(2, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * assign duplicate club
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function assign_duplicate_club()
    {
        $response = $this->authenticated()
        ->followingRedirects()
        ->post(
            route('league.assign-clubs', ['league' => $this->testleague->id]),
            ['club_id' => $this->testleague->clubs->first()->id, 'item_id' => $this->testleague->id]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }
}
