<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\Game;
use App\Models\Gym;
use App\Models\League;
use App\Traits\LeagueFSM;
use Illuminate\Support\Carbon;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueGameControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * index
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function index()
    {
        $response = $this->authenticated()
            ->get(route('league.game.index', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200)
            ->assertViewIs('game.league_game_list');
    }

    /**
     * upload
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function upload()
    {
        $response = $this->authenticated()
            ->get(route('league.upload.game', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200)
            ->assertViewIs('game.game_file_upload')
            ->assertViewHas('context', 'league');
    }

    /**
     * show_by_number
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function show_by_number()
    {
        $this->open_game_scheduling($this->testleague);
        $game = $this->testleague->games()->first();

        $response = $this->authenticated()
            ->get(route('league.game.show_bynumber', ['game_no' => $game->game_no, 'league' => $this->testleague]));

        $response->assertStatus(200)
                 ->assertJson($game->toArray());
    }

    /**
     * datata ble
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        $this->open_game_scheduling($this->testleague);

        $response = $this->authenticated()
            ->get(route('league.game.dt', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200)
                 ->assertJsonFragment(['club_id_home' => $this->testleague->games->first()->club_id_home]);
    }

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
        $league = $this->testleague;
        $this->freeze_league($league);
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
     * update home not OK
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function update_home_notok()
    {
        $league = $this->testleague;
        $this->freeze_league($league);
        $this->open_game_scheduling($league);
        $club = $this->testclub_assigned;
        $gym = Gym::factory()->create(['club_id' => $this->testclub_assigned->id, 'gym_no' => 9]);

        $game = Game::where('league_id', $league->id)
            ->where('club_id_home', $club->id)->first();
        $response = $this->authenticated()
            ->put(route('game.update_home', ['game' => $game]), [
                'gym_id' => $gym->id,
                'game_time' => '12:15',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['game_date']);
        //$response->dumpSession();
        $this->assertDatabaseMissing('games', ['id' => $game->id, 'gym_id' => $gym->id]);
    }

    /**
     * update_home OK
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function update_home_ok()
    {
        //$this->withoutExceptionHandling();
        $league = $this->testleague;
        $this->freeze_league($league);
        $this->open_game_scheduling($league);
        $club = $this->testclub_assigned;
        $gym = Gym::factory()->create(['club_id' => $this->testclub_assigned->id, 'gym_no' => 9]);

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
        $league = $this->testleague;
        $this->freeze_league($league);
        $this->open_game_scheduling($league);
        $club = $this->testclub_assigned;
        $gym = Gym::factory()->create(['club_id' => $this->testclub_assigned->id, 'gym_no' => 9]);

        $game = Game::where('league_id', $league->id)
            ->where('club_id_home', $club->id)->first();
        $response = $this->authenticated()
            ->put(route('game.update', ['game' => $game]), [
                'gym_id' => $gym->id,
                'game_date' => now(),
                'game_time' => '12:15',
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'Data is successfully added']);

        $this->assertDatabaseHas('games', ['id' => $game->id, 'gym_id' => $gym->id]);
    }

    /**
     * destroy_game
     *
     * @test
     * @group league
     * @group game
     * @group controller
     *
     * @return void
     */
    public function destroy_game()
    {
        $league = $this->testleague;
        $this->freeze_league($league);
        $this->open_game_scheduling($league);
        $this->assertDatabaseHas('games', ['league_id' => $league->id]);

        $response = $this->authenticated()
                         ->delete(route('league.game.destroy', ['league' => $league]));

        $response->assertStatus(200)
                ->assertJson(['success' => 'all good']);

        $this->assertDatabaseMissing('games', ['league_id' => $league->id]);
    }


}
