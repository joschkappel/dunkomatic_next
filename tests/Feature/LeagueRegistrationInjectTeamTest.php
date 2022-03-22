<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\Member;
use App\Models\Schedule;
use App\Models\League;
use App\Models\Game;
use App\Models\Gym;
use App\Models\Team;
use App\Models\LeagueSize;

use App\Enums\Role;
use App\Enums\LeagueState;
use Doctrine\DBAL\Schema\Schema;
use Tests\Support\Authentication;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LeagueRegistrationInjectTeamTest extends TestCase
{
    use Authentication;

    public function setUp(): void
    {
        static::$state = LeagueState::Registration;
        static::$initial_clubs = 3;
        static::$initial_teams = 3;
        parent::setUp();
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
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();

        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals( 4 , static::$testleague->state_count['size']);
        $this->assertEquals( 3 , static::$testleague->state_count['assigned']);
        $this->assertEquals( 3 , static::$testleague->state_count['registered']);
        $this->assertEquals( 0 , static::$testleague->state_count['charspicked']);
        $this->assertEquals( 0 , static::$testleague->state_count['generated']);

        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => static::$testleague->id]),
                ['club_id' => $c_toadd->id, 'item_id'=> static::$testleague->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$testleague->refresh();
        $this->assertEquals( 4 , static::$testleague->state_count['size']);
        $this->assertEquals( 4 , static::$testleague->state_count['assigned']);
        $this->assertEquals( 3 , static::$testleague->state_count['registered']);
        $this->assertEquals( 0 , static::$testleague->state_count['charspicked']);
        $this->assertEquals( 0 , static::$testleague->state_count['generated']);

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
        $c_toadd = Club::whereNotIn('id', static::$testleague->clubs->pluck('id'))->first();

        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => static::$testleague->id]),
                ['club_id' => $c_toadd->id, 'item_id'=> static::$testleague->id]
            );

        // now register the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->put(
                route('league.register.team', ['league'=>static::$testleague->id , 'team'=> $c_toadd->teams->first()->id ])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$testleague->refresh();
        $this->assertEquals( 4 , static::$testleague->state_count['size']);
        $this->assertEquals( 4 , static::$testleague->state_count['assigned']);
        $this->assertEquals( 4 , static::$testleague->state_count['registered']);
        $this->assertEquals( 0 , static::$testleague->state_count['charspicked']);
        $this->assertEquals( 0 , static::$testleague->state_count['generated']);

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
                route('league.team.withdraw', ['league' => static::$testleague->id]),
                ['team_id' => static::$testclub->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 2)
            ->assertDatabaseCount('games', 0);

        static::$testleague->refresh();
        $this->assertEquals( 4 , static::$testleague->state_count['size']);
        $this->assertEquals( 2 , static::$testleague->state_count['assigned']);
        $this->assertEquals( 2 , static::$testleague->state_count['registered']);
        $this->assertEquals( 0 , static::$testleague->state_count['charspicked']);
        $this->assertEquals( 0 , static::$testleague->state_count['generated']);

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
            ['club_id' => static::$testleague->clubs->first()->id, 'item_id'=> static::$testleague->id]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$testleague->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$testleague->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$testleague->refresh();
        $this->assertEquals( 4 , static::$testleague->state_count['size']);
        $this->assertEquals( 4 , static::$testleague->state_count['assigned']);
        $this->assertEquals( 3 , static::$testleague->state_count['registered']);
        $this->assertEquals( 0 , static::$testleague->state_count['charspicked']);
        $this->assertEquals( 0 , static::$testleague->state_count['generated']);
    }


}
