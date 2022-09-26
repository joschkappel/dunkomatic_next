<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueTeamControllerTest extends TestCase
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
     * assign_club
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function assign_club()
    {
        $c_toadd = $this->testclub_free;

        $response = $this->authenticated()
            ->post(route('league.assign-clubs', ['league' => $this->testleague]), [
                'club_id' => $c_toadd->id,
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('club_league', ['club_id' => $c_toadd->id, 'league_id' => $this->testleague->id]);
    }

    /**
     * deassign_club
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function deassign_club()
    {
        $response = $this->authenticated()
            ->delete(route('league.deassign-club', ['league' => $this->testleague, 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('club_league', ['club_id' => $this->testclub_assigned->id, 'league_id' => $this->testleague->id]);
    }

    /**
     * register team for league
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_register_team()
    {
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->put(route('league.register.team', [
                'league' => $this->testleague,
                'team' => $team,
            ]));

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => $this->testleague->id]);
    }

    /**
     * unregsiter team from leagu
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_unregister_team()
    {
        $team = $this->testleague->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.unregister.team', [
                'league' => $this->testleague,
                'team' => $team, ])
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'league_id' => $this->testleague->id]);
    }

    /**
     * register league for team
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function team_register_league()
    {
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->put(route('team.register.league', ['team' => $team]),
                ['league_id' => $this->testleague->id]
            );

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => $this->testleague->id]);
    }

    /**
     * pick a league character for a team
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_team_pickchar()
    {
        $team = $this->testleague->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]),
                ['team_id' => $team->id,
                    'league_no' => 2, ]
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_no' => 2]);
    }

    /**
     * release a league character for a team
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_team_releasechar()
    {
        $team = $this->testleague->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.releasechar', ['league' => $this->testleague]),
                ['team_id' => $team->id,
                    'league_no' => $team->league_no, ]
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'league_no' => $team->league_no]);
    }

    /**
     * inject
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function inject()
    {
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.inject', ['league' => $this->testleague]), [
                'team_id' => $team->id,
                'league_no' => 1,
            ]);

        // $response->dumpSession();
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $this->testleague->id]);
    }

    /**
     * withdraw
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function withdraw()
    {
        $team = $this->testleague->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.team.withdraw', ['league' => $this->testleague]), [
                'team_id' => $team->id,
            ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['league_no' => $team->league_no, 'league_id' => $this->testleague->id]);
    }
}
