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

    protected static $league;
    protected static $c_toadd;
    protected static $c_before;

    /**
     * Create pre-requs
     * 4 Clubs, 1 Team ech
     * 1 Schedule for 4
     * 1 League, State Selection
     *
     * @test
     * @group leaguemgmt
     *
     * @return void
     */
    public function create_prerequs()
    {
        $this->db_cleanup();
        // create data:  4 clubs with 1 team each
        static::$c_toadd = Club::factory()->hasTeams(1)->hasGyms(1)->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])->count(1)->create(['name' => 'CADD']);
        static::$c_before = Club::factory()->hasTeams(1)->hasGyms(1)->hasAttached(Member::factory()->count(1), ['role_id' => Role::ClubLead()])->count(3)->create(['name' => 'CBEFORE']);
        // create schedule (4 teams)
        $schedule = Schedule::factory()->create(['name' => 'testschedule']);
        $this->authenticated()
            ->post(route('schedule_event.store', ['schedule' => $schedule]), [
                'startdate' => Carbon::now()->addDays(32),
            ]);
        // create league
        $size = LeagueSize::where('size',4)->first();
        static::$league = League::factory()->create(['name' => 'testleague', 'schedule_id' => $schedule->id, 'league_size_id' => $size->id]);
        static::$league->state = LeagueState::Registration();
        static::$league->save();

        // assign clubs to league
        static::$league->clubs()->attach([
            static::$c_before[0]->id =>  ['league_no' => 1, 'league_char' => 'A'],
            static::$c_before[1]->id =>  ['league_no' => 2, 'league_char' => 'B'],
            static::$c_before[2]->id =>  ['league_no' => 3, 'league_char' => 'C'],
        ]);
        // register teams
        static::$c_before[0]->teams->first()->update(['league_id' => static::$league->id]);
        static::$c_before[1]->teams->first()->update(['league_id' => static::$league->id]);
        static::$c_before[2]->teams->first()->update(['league_id' => static::$league->id]);

        $this->assertDatabaseHas('leagues', ['id' => static::$league->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$league->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        static::$league->refresh();
        $this->assertEquals( 4 , static::$league->state_count['size']);
        $this->assertEquals( 3 , static::$league->state_count['assigned']);
        $this->assertEquals( 3 , static::$league->state_count['registered']);
        $this->assertEquals( 0 , static::$league->state_count['charspicked']);
        $this->assertEquals( 0 , static::$league->state_count['generated']);

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

        // now add the new club/
        $response = $this->authenticated()
            ->followingRedirects()
            ->post(
                route('league.assign-clubs', ['league' => static::$league->id]),
                ['club_id' => static::$c_toadd[0]->id, 'item_id'=> static::$league->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$league->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$league->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$league->refresh();
        $this->assertEquals( 4 , static::$league->state_count['size']);
        $this->assertEquals( 4 , static::$league->state_count['assigned']);
        $this->assertEquals( 3 , static::$league->state_count['registered']);
        $this->assertEquals( 0 , static::$league->state_count['charspicked']);
        $this->assertEquals( 0 , static::$league->state_count['generated']);

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
        // now register the team
        $response = $this->authenticated()
            ->followingRedirects()
            ->put(
                route('league.register.team', ['league'=>static::$league->id , 'team'=> static::$c_toadd[0]->teams->first()->id ])
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$league->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$league->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$league->refresh();
        $this->assertEquals( 4 , static::$league->state_count['size']);
        $this->assertEquals( 4 , static::$league->state_count['assigned']);
        $this->assertEquals( 4 , static::$league->state_count['registered']);
        $this->assertEquals( 0 , static::$league->state_count['charspicked']);
        $this->assertEquals( 0 , static::$league->state_count['generated']);

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
                route('league.team.withdraw', ['league' => static::$league->id]),
                ['team_id' => static::$c_toadd[0]->teams->first()->id]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$league->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$league->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 3)
            ->assertDatabaseCount('games', 0);

        static::$league->refresh();
        $this->assertEquals( 4 , static::$league->state_count['size']);
        $this->assertEquals( 3 , static::$league->state_count['assigned']);
        $this->assertEquals( 3 , static::$league->state_count['registered']);
        $this->assertEquals( 0 , static::$league->state_count['charspicked']);
        $this->assertEquals( 0 , static::$league->state_count['generated']);

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
            route('league.assign-clubs', ['league' => static::$league->id]),
            ['club_id' => static::$c_toadd[0]->id, 'item_id'=> static::$league->id]
        );
        $response->assertStatus(200);
        $this->assertDatabaseHas('leagues', ['id' => static::$league->id, 'state' => LeagueState::Registration()])
            ->assertDatabaseMissing('games', ['league_id' => static::$league->id])
            ->assertDatabaseCount('clubs', 4)
            ->assertDatabaseCount('teams', 4)
            ->assertDatabaseCount('club_league', 4)
            ->assertDatabaseCount('games',0 );

        static::$league->refresh();
        $this->assertEquals( 4 , static::$league->state_count['size']);
        $this->assertEquals( 4 , static::$league->state_count['assigned']);
        $this->assertEquals( 3 , static::$league->state_count['registered']);
        $this->assertEquals( 0 , static::$league->state_count['charspicked']);
        $this->assertEquals( 0 , static::$league->state_count['generated']);
    }

    /**
     * db_cleanup
     *
     * @test
     * @group leaguemgmt_X
     *
     * @return void
     */
    public function db_cleanup()
    {
        /// clean up DB
        Game::whereNotNull('id')->delete();
        Gym::whereNotNull('id')->delete();
        Team::whereNotNull('id')->delete();
        foreach (Club::all() as $c) {
            $c->leagues()->detach();
            $c->members()->detach();
            $c->delete();
        }
        $league = League::where('name', 'testleague')->first();
        if (isset($league)) {
            $league->schedule->events()->delete();
            $league->delete();
        }

        $schedule = Schedule::where('name', 'testschedule')->first();
        if (isset($schedule)){
            if ($schedule->events()->exists()){
                $schedule->events()->delete();
            }
            $schedule->delete();
        }

        //League::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0)
            ->assertDatabaseCount('clubs', 0)
            ->assertDatabaseCount('teams', 0)
            ->assertDatabaseCount('games', 0);
    }

}
