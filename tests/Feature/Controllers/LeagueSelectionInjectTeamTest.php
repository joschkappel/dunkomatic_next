<?php

namespace Tests\Feature\Controllers;

use App\Enums\LeagueState;
use App\Models\Club;
use App\Models\League;
use App\Models\Team;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueSelectionInjectTeamTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(3, 3)->create();
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

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(3, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague]),
                ['club_id' => $c_toadd->id, 'item_id' => $this->testleague->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(3, $this->testleague->state_count['charspicked']);
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
        // now register the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->put(
                route('league.register.team', ['league' => $this->testleague, 'team' => $c_toadd->teams->first()])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(4, $this->testleague->state_count['registered']);
        $this->assertEquals(3, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * select league team no
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function select_leagueteamno()
    {
        $c_toadd = $this->testclub_free;

        // now register the team with existing no
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.pickchar', ['league' => $this->testleague->id]),
                [
                    'league_id' => $this->testleague->id,
                    'league_no' => 1, 'team_id' => $c_toadd->teams->first()->id,
                ]
            );
        $response->assertStatus(410);

        // now do with correct no
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.pickchar', ['league' => $this->testleague->id]),
                [
                    'league_id' => $this->testleague->id,
                    'league_no' => 4, 'team_id' => $c_toadd->teams->first()->id,
                ]
            );
        $response->assertStatus(200);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(4, $this->testleague->state_count['registered']);
        $this->assertEquals(4, $this->testleague->state_count['charspicked']);
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
                route('league.withdraw.team', ['league' => $this->testleague->id, 'team' => $this->testclub_assigned->teams->first()->id])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Selection()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(2, $this->testleague->state_count['assigned']);
        $this->assertEquals(2, $this->testleague->state_count['registered']);
        $this->assertEquals(2, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }
}
