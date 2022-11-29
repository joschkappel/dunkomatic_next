<?php

namespace Tests\Feature\Controllers;

use App\Events\LeagueTeamCharUpdated;
use App\Models\League;
use App\Models\Team;
use Illuminate\Support\Facades\Event;
use Tests\Support\Authentication;
use Tests\TestCase;

class PickCharTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_1;
    private $testclub_2;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 2)->create();
        $this->testclub_1 = $this->testleague->clubs->first();
        $this->testclub_2 = $this->testleague->clubs->last();
    }


    /**
     * 1 club oicks 1 char
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function one_club_one_chars()
    {
        Event::fake();

        $team_1 = $this->testclub_1->teams->first();
        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 0 );

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team_1->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);

        Event::assertDispatched(LeagueTeamCharUpdated::class, function ($e) use ($team_1) {
            return $e->league->id == $team_1->league->id;
        });

        // check teams has picked the right character:
        $this->assertDatabaseHas('teams',[
            'id' => $team_1->id,
            'league_id' => $team_1->league->id,
            'league_char' => 'B',
            'league_no' => '2'
        ]);

        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 1 );


    }

    /**
     * 2 clubs pick 2 chars
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function two_clubs_two_chars()
    {
        Event::fake();

        $team_1 = $this->testleague->teams->first();
        $team_2 = $this->testleague->teams->last();

        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 0 );
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
        ->where('league_no', '4')->count() == 0 );

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team_1->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);


        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team_2->id,
                'league_no' => 4,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);

        Event::assertDispatchedTimes(LeagueTeamCharUpdated::class, 2);

        // check teams has picked the right character:
        $this->assertDatabaseHas('teams',[
            'id' => $team_1->id,
            'league_id' => $team_1->league->id,
            'league_char' => 'B',
            'league_no' => '2'
        ]);
        $this->assertDatabaseHas('teams',[
            'id' => $team_2->id,
            'league_id' => $team_2->league->id,
            'league_char' => 'D',
            'league_no' => '4'
        ]);

        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 1 );
        $this->assertTrue( Team::where('league_id', $team_2->league->id)
            ->where('league_no', '4')->count() == 1 );

    }
    /**
     * 2 clubs pick same char
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function two_clubs_same_char()
    {
        Event::fake();

        $team_1 = $this->testleague->teams->first();
        $team_2 = $this->testleague->teams->last();

        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 0 );
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
        ->where('league_no', '2')->count() == 0 );

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team_1->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);


        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team_2->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(410)
            ->assertSessionHasNoErrors()
            ->assertJson(['message' => 'number_taken']);;

        Event::assertDispatchedTimes(LeagueTeamCharUpdated::class, 1);

        // check teams has picked the right character:
        $this->assertDatabaseHas('teams',[
            'id' => $team_1->id,
            'league_id' => $team_1->league->id,
            'league_char' => 'B',
            'league_no' => '2'
        ]);
        $this->assertDatabaseMissing('teams',[
            'id' => $team_2->id,
            'league_id' => $team_2->league->id,
            'league_char' => 'B',
            'league_no' => '2'
        ]);

        // check no no else has got it
        $this->assertTrue( Team::where('league_id', $team_1->league->id)
            ->where('league_no', '2')->count() == 1 );

    }
}
