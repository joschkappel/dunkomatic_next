<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\League;
use App\Models\Game;
use App\Models\Gym;
use App\Models\Team;

use App\Enums\Role;
use App\Enums\LeagueState;
use App\Traits\LeagueFSM;
use Tests\Support\Authentication;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LeagueSchedulingInjectTeamTest extends TestCase
{
    use Authentication, LeagueFSM;

    public function setUp(): void
    {
        static::$state = LeagueState::Freeze;
        static::$initial_clubs = 3;
        static::$initial_teams = 3;
        parent::setUp();
    }

    /**
     * Inject new club and team
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function inject_team()
    {
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();
        $this->open_scheduling(static::$testleague);

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 12);

        static::$testleague->refresh();
        $this->assertCount(3, static::$testleague->games_notime);
        $this->assertCount(3, static::$testleague->games_noshow);
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(3, static::$testleague->state_count['assigned']);
        $this->assertEquals(3, static::$testleague->state_count['registered']);
        $this->assertEquals(3, static::$testleague->state_count['charspicked']);
        $this->assertEquals(12, static::$testleague->state_count['generated']);
        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.inject', ['league' => static::$testleague->id]),
                ['league_no' => 4, 'team_id' => $c_toadd->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 12);

        static::$testleague->refresh();
        $this->assertCount(0, static::$testleague->games_notime);
        $this->assertCount(0, static::$testleague->games_noshow);
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(4, static::$testleague->state_count['assigned']);
        $this->assertEquals(4, static::$testleague->state_count['registered']);
        $this->assertEquals(4, static::$testleague->state_count['charspicked']);
        $this->assertEquals(12, static::$testleague->state_count['generated']);
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
        $this->open_scheduling(static::$testleague);

        // now withdraw a team
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.team.withdraw', ['league' => static::$testleague->id]),
                ['team_id' => static::$testleague->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 12);

        static::$testleague->refresh();
        $this->assertCount(3, static::$testleague->games_notime);
        $this->assertCount(6, static::$testleague->games_noshow);
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(2, static::$testleague->state_count['assigned']);
        $this->assertEquals(2, static::$testleague->state_count['registered']);
        $this->assertEquals(2, static::$testleague->state_count['charspicked']);
        $this->assertEquals(12, static::$testleague->state_count['generated']);
    }

    /**
     * re-add team
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function reinject_team()
    {
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();
        $this->open_scheduling(static::$testleague);
        // now re-add the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.inject', ['league' => static::$testleague->id]),
                ['league_no' => 1, 'team_id' => $c_toadd->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 12);

        static::$testleague->refresh();
        $this->assertCount(3, static::$testleague->games_notime);
        $this->assertCount(3, static::$testleague->games_noshow);
        $this->assertEquals(4, static::$testleague->state_count['size']);
        $this->assertEquals(4, static::$testleague->state_count['assigned']);
        $this->assertEquals(4, static::$testleague->state_count['registered']);
        $this->assertEquals(4, static::$testleague->state_count['charspicked']);
        $this->assertEquals(12, static::$testleague->state_count['generated']);
    }

}
