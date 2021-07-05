<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;
use App\Models\Game;
use App\Models\Team;
use App\Models\Gym;
use App\Models\Schedule;
use Carbon\Carbon;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class LeagueGameControllerTest extends TestCase
{
    use Authentication;

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
      $this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::factory()->hasTeams(2)->hasGyms(1)->create(['name'=>'testteamclub']);
      $club2 = Club::factory()->hasTeams(2)->hasGyms(1)->create(['name'=>'testteamclub2']);
      $league = League::factory()->create(['name'=>'testleague']);
      foreach ($club->teams as $t){
        $t->league()->associate($league)->save();
      }
      $club->teams[0]->update(['league_char'=>'A', 'league_no'=>1]);
      $club->teams[1]->update(['league_char'=>'B', 'league_no'=>2]);
      foreach ($club2->teams as $t){
        $t->league()->associate($league)->save();
      }
      $club2->teams[0]->update(['league_char'=>'C', 'league_no'=>3]);
      $club2->teams[1]->update(['league_char'=>'D', 'league_no'=>4]);
      // generate the events
      $response = $this->authenticated( )
                        ->post(route('schedule_event.store',['schedule'=>$league->schedule]), [
                          'startdate' => Carbon::now()->addDays(32),
                      ]);

      $this->assertDatabaseMissing('games', ['league_id' => $league->id]);
      $response = $this->authenticated( )
                        ->post(route('league.game.store',['league'=>$league]));

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
      $league = League::where('name','testleague')->first();
      $club = Club::where('name','testteamclub')->first();
      $gym = Gym::factory()->create(['club_id'=>$club->id,'gym_no'=>8]);

      $game = Game::where('league_id',$league->id)
                  ->where('club_id_home',$club->id)->first();
      $response = $this->authenticated( )
                        ->put(route('game.update',['game'=>$game]),[
                          'gym_id' => $gym->id,
                          'game_time' => '12:15',
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['game_date']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('games', ['id'=>$game->id,'gym_id'=>$gym->id]);
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
      $league = League::where('name','testleague')->first();
      $club = Club::where('name','testteamclub')->first();
      $gym = Gym::factory()->create(['club_id'=>$club->id,'gym_no'=>9]);

      $game = Game::where('league_id',$league->id)
                  ->where('club_id_home',$club->id)->first();
      $response = $this->authenticated( )
                        ->put(route('game.update',['game'=>$game]),[
                          'gym_id' => $gym->id,
                          'gym_no' => $gym->gym_no,
                          'game_date' => now(),
                          'game_time' => '12:15',
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('games', ['id'=>$game->id,'gym_id'=>$gym->id]);
    }

    /**
     * db_cleanup
     *
     * @test
     * @group league
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
}
