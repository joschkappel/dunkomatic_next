<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;
use App\Models\Game;
use App\Models\Gym;
use App\Traits\LeagueFSM;
use Illuminate\Support\Carbon;

use Tests\TestCase;
use Tests\Support\Authentication;

class LeagueGameControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    /**
     * store_ok
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
        $league = static::$testleague;
        $this->close_selection($league);
        // generate the events

        $response = $this->authenticated()
            ->post(route('schedule_event.store', ['schedule' => $league->schedule]), [
                'startdate' => Carbon::now()->addDays(32),
            ]);

        $this->assertDatabaseMissing('games', ['league_id' => $league->id]);
        $response = $this->authenticated()
            ->post(route('league.game.store', ['league' => $league]));

        $response->assertStatus(200)
            ->assertJson(['success' => 'all good']);

        $this->assertDatabaseHas('games', ['league_id' => $league->id]);
    }

    /**
     * update not OK
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        //$this->withoutExceptionHandling();
        $league = static::$testleague;
        $this->close_selection($league);
        $this->close_freeze($league);
        $club = static::$testclub;
        $gym = Gym::factory()->create(['club_id' => static::$testclub->id, 'gym_no' => 9]);

        $game = Game::where('league_id', $league->id)
            ->where('club_id_home', $club->id)->first();
        $response = $this->authenticated()
            ->put(route('game.update_home', ['game' => $game]), [
                'gym_id' => $gym->id,
                'game_time' => '12:15',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['game_date']);;
        //$response->dumpSession();
        $this->assertDatabaseMissing('games', ['id' => $game->id, 'gym_id' => $gym->id]);
    }
    /**
     * update OK
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        //$this->withoutExceptionHandling();
        $league = static::$testleague;
        $this->close_selection($league);
        $this->close_freeze($league);
        $club = static::$testclub;
        $gym = Gym::factory()->create(['club_id' => static::$testclub->id, 'gym_no' => 9]);

        $game = Game::where('league_id', $league->id)
            ->where('club_id_home', $club->id)->first();
        $response = $this->authenticated()
            ->put(route('game.update_home', ['game' => $game]), [
                'gym_id' => $gym->id,
                'gym_no' => $gym->gym_no,
                'game_date' => now(),
                'game_time' => '12:15',
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('games', ['id' => $game->id, 'gym_id' => $gym->id]);
    }
}
