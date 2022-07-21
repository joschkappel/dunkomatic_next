<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\League;

use App\Enums\LeagueState;
use App\Traits\LeagueFSM;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueSchedulingInjectTeamTest extends TestCase
{
    use Authentication, LeagueFSM;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(3,3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
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
        $c_toadd =$this->testclub_free;
        $this->reopen_game_scheduling($this->testleague);

        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 12);

        $this->testleague->refresh();
        $this->assertCount(0, $this->testleague->games_notime);
        $this->assertCount(3, $this->testleague->games_noshow);
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(3, $this->testleague->state_count['assigned']);
        $this->assertEquals(3, $this->testleague->state_count['registered']);
        $this->assertEquals(3, $this->testleague->state_count['charspicked']);
        $this->assertEquals(12, $this->testleague->state_count['generated']);
        // now add the new club/team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.inject', ['league' => $this->testleague->id]),
                ['league_no' => 4, 'team_id' => $c_toadd->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 12);

        $this->testleague->refresh();
        $this->assertCount(0, $this->testleague->games_notime);
        $this->assertCount(0, $this->testleague->games_noshow);
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(4, $this->testleague->state_count['registered']);
        $this->assertEquals(4, $this->testleague->state_count['charspicked']);
        $this->assertEquals(12, $this->testleague->state_count['generated']);
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
        $this->reopen_game_scheduling($this->testleague);

        // now withdraw a team
        $response = $this->authenticated()
            ->followingRedirects()
            ->delete(
                route('league.team.withdraw', ['league' => $this->testleague->id]),
                ['team_id' => $this->testleague->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 12);

        $this->testleague->refresh();
        $this->assertCount(0, $this->testleague->games_notime);
        $this->assertCount(6, $this->testleague->games_noshow);
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(2, $this->testleague->state_count['assigned']);
        $this->assertEquals(2, $this->testleague->state_count['registered']);
        $this->assertEquals(2, $this->testleague->state_count['charspicked']);
        $this->assertEquals(12, $this->testleague->state_count['generated']);
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
        $c_toadd = $this->testclub_free;
        $this->reopen_game_scheduling($this->testleague);
        // now re-add the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.team.inject', ['league' => $this->testleague->id]),
                ['league_no' => 1, 'team_id' => $c_toadd->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => $this->testleague->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $this->testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games', 12);

        $this->testleague->refresh();
        $this->assertCount(0, $this->testleague->games_notime);
        $this->assertCount(3, $this->testleague->games_noshow);
        $this->assertEquals(4, $this->testleague->state_count['size']);
        $this->assertEquals(4, $this->testleague->state_count['assigned']);
        $this->assertEquals(4, $this->testleague->state_count['registered']);
        $this->assertEquals(4, $this->testleague->state_count['charspicked']);
        $this->assertEquals(12, $this->testleague->state_count['generated']);
    }

}
