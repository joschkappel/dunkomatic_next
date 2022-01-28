<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;

class LeagueTeamControllerTest extends TestCase
{
    use Authentication;

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

        // create data:  1 club with 4 teams 
        // 1 league (size 4)
        $club = Club::factory()->hasTeams(4)->create(['name' => 'testteamclub']);
        $league = League::factory()->create(['name' => 'testleague']);

        $response = $this->authenticated()
            ->post(route('league.assign-clubs', ['league' => $league]), [
                'club_id' => $club->id
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('club_league', ['club_id' => $club->id, 'league_id' => $league->id, 'league_no' => 1]);

        // assign same club again 
        $response = $this->authenticated()
            ->post(route('league.assign-clubs', ['league' => $league]), [
                'club_id' => $club->id
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('club_league', ['club_id' => $club->id, 'league_id' => $league->id, 'league_no' => 2]);
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
        $league = League::where('name', 'testleague')->first();

        $club = $league->clubs()->first();

        $response = $this->authenticated()
            ->delete(route('league.deassign-club', ['league' => $league, 'club' => $club]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('club_league', ['club_id' => $club->id, 'league_id' => $league->id, 'league_no' => 2]);
        $this->assertDatabaseHas('club_league', ['club_id' => $club->id, 'league_id' => $league->id, 'league_no' => 1]);
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
        $league = League::where('name', 'testleague')->first();
        $club = $league->clubs()->first();
        $team = $club->teams->first();

        $response = $this->authenticated()
            ->put(route('league.register.team', [
                'league' => $league,
                'team' => $team
            ]));

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => $league->id]);
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
        $league = League::where('name', 'testleague')->first();
        $team = $league->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.unregister.team', [
                'league' => $league,
                'team' => $team ])
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'league_id' => $league->id]);
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
        $league = League::where('name', 'testleague')->first();
        $club = $league->clubs()->first();
        $team = $club->teams->first();

        $response = $this->authenticated()
            ->put(route('team.register.league', ['team' => $team ]),
            ['league_id' => $league->id]
            );

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['id' => $team->id, 'league_id' => $league->id]);
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
        $league = League::where('name', 'testleague')->first();
        $team = $league->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', [ 'league' => $league ]),
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
     * unpick a league character for a team
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_team_unpickchar()
    {
        $league = League::where('name', 'testleague')->first();
        $team = $league->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.unpickchar', [ 'league' => $league ]),
                ['team_id' => $team->id, 
                 'league_no' => 2]
            );

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['id' => $team->id, 'league_no' => 2]);
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
        //$this->withoutExceptionHandling();
        // create data:  1 club with 5 teams assigned to 1 league each
        $club = Club::where('name', 'testteamclub')->first();
        $league = League::where('name', 'testleague')->first();
        $team = $club->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.inject', ['league' => $league]), [
                'team_id' => $team->id,
                'league_no' => 1,
            ]);

        // $response->dumpSession();
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $league->id]);
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
        //$this->withoutExceptionHandling();
        $club = Club::where('name', 'testteamclub')->first();
        $league = League::where('name', 'testleague')->first();
        $team = $league->teams->first();

        $response = $this->authenticated()
            ->delete(route('league.team.withdraw', ['league' => $league]), [
                'team_id' => $team->id,
            ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseMissing('teams', ['league_no' => $team->league_no, 'league_id' => $league->id]);
    }

    /**
     * db_cleanup
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function db_cleanup()
    {
        /// clean up DB
        League::whereNotNull('id')->delete();
        $club = Club::where('name', 'testteamclub')->first();
        $club->teams()->delete();
        Club::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0)
            ->assertDatabaseCount('clubs', 0);
    }
}
