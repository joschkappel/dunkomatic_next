<?php

namespace Tests\Unit;

use App\Enums\LeagueState;
use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;

class LeagueTeamControllerTest extends TestCase
{
    use Authentication;

    public function setUp(): void
    {
        static::$state = LeagueState::Selection;
        static::$initial_clubs = 3;
        static::$initial_teams = 3;
        parent::setUp();
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
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();

        $response = $this->authenticated()
            ->post(route('league.assign-clubs', ['league' => static::$testleague]), [
                'club_id' => $c_toadd->id
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('club_league', ['club_id' => $c_toadd->id, 'league_id' => static::$testleague->id]);

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
            ->delete(route('league.deassign-club', ['league' => static::$testleague, 'club' => static::$testclub]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('club_league', ['club_id' => static::$testclub->id, 'league_id' => static::$testleague->id]);
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->put(route('league.register.team', [
                'league' => static::$testleague,
                'team' => $team
            ]));

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => static::$testleague->id]);
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
        $team = static::$testleague->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.unregister.team', [
                'league' => static::$testleague,
                'team' => $team ])
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'league_id' => static::$testleague->id]);
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->put(route('team.register.league', ['team' => $team ]),
            ['league_id' => static::$testleague->id]
            );

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => static::$testleague->id]);
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
        $team = static::$testleague->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', [ 'league' => static::$testleague ]),
                ['team_id' => $team->id,
                 'league_no' => 2]
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
        $team = static::$testleague->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.releasechar', [ 'league' => static::$testleague ]),
                ['team_id' => $team->id,
                 'league_no' => $team->league_no]
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.inject', ['league' => static::$testleague]), [
                'team_id' => $team->id,
                'league_no' => 1,
            ]);

        // $response->dumpSession();
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => static::$testleague->id]);
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
        $team = static::$testleague->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.team.withdraw', ['league' => static::$testleague]), [
                'team_id' => $team->id,
            ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['league_no' => $team->league_no, 'league_id' => static::$testleague->id]);
    }

}
