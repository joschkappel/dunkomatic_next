<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use App\Models\Game;
use App\Models\Gym;
use App\Models\Schedule;
use App\Models\Team;
use Illuminate\Support\Carbon;

use Tests\TestCase;
use Tests\Support\Authentication;

class GameValidationTest extends TestCase
{
    use Authentication;

    protected static $league;
    protected static $club;
    protected static $club2;
    protected static $game;
    protected static $guest;
    protected static $home;

    /**
      * game validation
      *
      * @test
      * @group game
      * @group validation
      *
      * @return void
    */
    public function prepare_games(): void
    {
      // create data:  1 club with 5 teams assigned to 1 league each
      static::$club = Club::factory()->hasTeams(2)->hasGyms(1)->create(['name'=>'testteamclub']);
      static::$club2 = Club::factory()->hasTeams(2)->hasGyms(1)->create(['name'=>'testteamclub2']);
      static::$league = League::factory()->custom()->create(['name'=>'testleague']);

      foreach (static::$club->teams as $t){
        $t->league()->associate(static::$league)->save();
      }
      static::$club->teams[0]->update(['league_char'=>'A', 'league_no'=>1]);
      static::$club->teams[1]->update(['league_char'=>'B', 'league_no'=>2]);
      foreach (static::$club2->teams as $t){
        $t->league()->associate(static::$league)->save();
      }
      static::$club2->teams[0]->update(['league_char'=>'C', 'league_no'=>3]);
      static::$club2->teams[1]->update(['league_char'=>'D', 'league_no'=>4]);
      // generate the events
      $response = $this->authenticated( )
                        ->post(route('schedule_event.store',['schedule'=>static::$league->schedule]), [
                          'startdate' => Carbon::now()->addDays(32),
                      ]);

      $this->assertDatabaseMissing('games', ['league_id' => static::$league->id]);
      $response = $this->authenticated( )
                        ->post(route('league.game.store',['league'=>static::$league]));

      $response->assertStatus(200)
                ->assertJson(['success' => 'all good']);

      $this->assertDatabaseHas('games', ['league_id' => static::$league->id]);

      $game = Game::where('game_no', 4)->first();

      static::$game = $game->id;
      static::$guest = $game->team_id_guest;
      static::$home = $game->team_id_home;
    }

    /**
      * game validation
      *
      * @test
      * @dataProvider gameForm
      * @group game
      * @group validation
      *
      * @return void
      */
    public function game_form_validation($formInput, $formInputValue): void
    {
      $game = Game::find(static::$game);

      $response = $this->authenticated()
           ->put(route('game.update', ['game'=>$game]), [$formInput => $formInputValue]);

//      $response->dumpSession();
      $response->assertSessionHasErrors($formInput);
    }

    /**
     * db_cleanup
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
   public function db_cleanup()
   {
        /// clean up DB
        Game::whereNotNull('id')->delete();
        Gym::whereNotNull('id')->delete();
        Team::whereNotNull('id')->delete();
        Club::whereNotNull('id')->delete();
        $league = League::where('name','testleague')->first();
        $league->schedule->events()->delete();
        $league->delete();
        Schedule::whereNotNull('id')->delete();
        //League::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0);
   }

    public function gameForm(): array
    {
        return [
            'gym missing' => ['gym_id', ''],
            'gym not existing' => ['gym_id', 5000],
            'game date missing' => ['game_date', ''],
            'game date no date format' => ['game_date', 'AAAA'],
            'game date too old' => ['game_date', Carbon::now()->addDays(-10)],
            'game time missing' => ['game_time', ''],
            'game time no time format' => ['game_time', 'BBBB'],
            'game time wrong time format' => ['game_time', '25:10'],
            'game no out of range' => ['game_no', 200],
            'game no out of range 2' => ['game_no', 0],
            'game no no integer' => ['game_no', 'AAA'],
            'game no not unique' => ['game_no', 6 ],
            'team id home not existing' => ['team_id_home', 5000],
            'team id home same as guest' => ['team_id_home', static::$guest],
            'team id guest not existing' => ['team_id_guest', 5000],
            'team id guest same as home' => ['team_id_guest', static::$home],
        ];
    }
}
