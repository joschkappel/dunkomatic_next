<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\League;

use App\Enums\LeagueState;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueAssignmentInjectTeamTest extends TestCase
{
    use Authentication;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->assigned(3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * Inject new club
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
        $this->assertEquals(0, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);

        $clubs = $this->testleague->clubs->pluck('id')->toArray();
        $clubs[] = $c_toadd->id;

        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague->id]),
                ['assignedClubs' => $clubs]
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
        $this->assertEquals(0, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * deasasing 1 club
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function deassign_club()
    {
        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.deassign-club', ['league' => $this->testleague, 'club' => $this->testclub_assigned])
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
        $this->assertEquals(0, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * modify assignment
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function modify_assignment()
    {
        $clubs[] = $this->testclub_assigned->id;
        $clubs[] = $this->testclub_free->id;

        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague]),
                ['assignedClubs' => $clubs]
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
        $this->assertEquals(0, $this->testleague->state_count['registered']);
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
                ['club_id' => $this->testclub_assigned->id, 'item_id' => $this->testleague->id]
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
        $this->assertEquals(0, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }

    /**
     * deasasing duplicate club
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function deassign_duplicate_club()
    {
        // now add a duplicate
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => $this->testleague]),
                ['club_id' => $this->testclub_assigned->id, 'item_id' => $this->testleague->id]
            );

        // and remove 1
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.deassign-club', ['league' => $this->testleague, 'club' => $this->testclub_assigned])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        $this->testleague->refresh();
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(0, $this->testleague->state_count['registered']);
        $this->assertEquals(0, $this->testleague->state_count['charspicked']);
        $this->assertEquals(0, $this->testleague->state_count['generated']);
    }
}
