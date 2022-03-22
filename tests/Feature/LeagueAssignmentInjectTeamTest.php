<?php

namespace Tests\Feature;

use App\Models\Club;

use App\Enums\LeagueState;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueAssignmentInjectTeamTest extends TestCase
{
    use Authentication;

    public function setUp(): void
    {
        static::$state = LeagueState::Assignment;
        static::$initial_clubs = 3;
        parent::setUp();
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
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(3, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);

        $clubs = static::$testleague->clubs->pluck('id')->toArray();
        $clubs[] = $c_toadd->id;

        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => static::$testleague->id]),
                ['assignedClubs' => $clubs]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(4, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);
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
                route('league.deassign-club', ['league' => static::$testleague, 'club' => static::$testclub])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(2, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);
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
        $clubs[] = static::$testclub->id;
        $clubs[] = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first()->id;

        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => static::$testleague]),
                ['assignedClubs' => $clubs]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(2, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);
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
                route('league.assign-clubs', ['league' => static::$testleague->id]),
                ['club_id' => static::$testclub->id, 'item_id' => static::$testleague->id]
            );
        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(4, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);
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
                route('league.assign-clubs', ['league' => static::$testleague]),
                ['club_id' => static::$testclub->id, 'item_id' => static::$testleague->id]
            );

        // and remove 1
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.deassign-club', ['league' => static::$testleague, 'club' => static::$testclub])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Assignment()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(3, static::$testleague->state_count['assigned']);
        $this->assertEquals(0, static::$testleague->state_count['registered']);
        $this->assertEquals(0, static::$testleague->state_count['charspicked']);
        $this->assertEquals(0, static::$testleague->state_count['generated']);
    }
}
