<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\Team;
use App\Models\League;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class TeamControllerTest extends TestCase
{
    use Authentication;

    /**
     * sb_league
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function sb_league()
    {

      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::factory()->hasTeams(2)->create(['name'=>'testteamclub']);
      $league = League::factory()->create(['name'=>'testleague']);
      foreach ($club->teams as $t){
        $t->league()->associate($league)->save();
      }
      $team = $club->teams->first();

      $response = $this->authenticated()
                        ->get(route('league.team.sb',['league'=>$league]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJson([['id'=>$team->id,'text'=>$club->shortname.$team->team_no]]);
    }
    /**
     * sb_freeteam
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function sb_freeteam()
    {

      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $this->region->teams()->whereNull('league_id')->orWhere(function($query) use($league)
        { $query->where('league_id', $league->id)
                ->whereNull('league_no');
        })->with('club')->first();

      $response = $this->authenticated()
                        ->get(route('team.free.sb',['league'=>$league]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJson([['id'=>$team->id,'text'=>$club->shortname.$team->team_no.' ('.$team->league_prev.')']]);
    }
    /**
     * assign_league
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function assign_league()
    {
      //$this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $club->teams->first();

      $response = $this->authenticated()
                        ->put(route('team.assign-league'),[
                          'club_id' => $club->id,
                          'league_id' => $league->id,
                          'team_id' => $team->id,
                          'league_no' => 1,
                        ]);

      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors()
          ->assertHeader('Location', route('club.dashboard', ['language'=>'de','club'=>$club]));
      //$response->dump();
      $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $league->id]);
    }
    /**
     * deassign_league
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function deassign_league()
    {
      //$this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $league->teams->first();

      $response = $this->authenticated()
                        ->delete(route('team.deassign-league'),[
                          'club_id' => $club->id,
                          'league_id' => $league->id,
                          'team_id' => $team->id,
                        ]);

      $response
          ->assertStatus(200)
          ->assertSessionHasNoErrors();
      //$response->dump();
      $this->assertDatabaseMissing('teams', ['league_no' => $team->league_no, 'league_id' => $league->id]);
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
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $league->teams->first();

      $response = $this->authenticated()
                        ->delete(route('league.team.withdraw',['league'=>$league]),[
                          'team_id' => $team->id,
                        ]);

      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors();
      //$response->dump();
      $this->assertDatabaseMissing('teams', ['league_no' => $team->league_no, 'league_id' => $league->id]);
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
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $club->teams->first();

      $response = $this->authenticated()
                        ->post(route('league.team.inject',['league'=>$league]),[
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
     * plan_leagues
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function plan_leagues()
    {
      //$this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();

      $response = $this->authenticated()
                        ->get(route('team.plan-leagues',['language'=>'de' , 'club'=>$club]));

      $response->assertStatus(200)
               ->assertViewIs('team.teamleague_dashboard')
               ->assertViewHas('club',$club);
    }
    /**
     * store_plan
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function store_plan()
    {
      //$this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $club->teams->first();

      $response = $this->authenticated()
                        ->post(route('team.store-plan'),[
                          'selSize:'.$league->id.':'.$team->id => 1,
                        ]);

      $response
          ->assertStatus(200)
          ->assertSessionHasNoErrors();
      //$response->dump();
      $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $league->id]);
    }
    /**
     * propose_combination
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function propose_combination()
    {
      //$this->withoutExceptionHandling();
      // create data:  1 club with 5 teams assigned to 1 league each
      $club = Club::where('name','testteamclub')->first();
      $league = League::where('name','testleague')->first();
      $team = $club->teams->first();

      $response = $this->authenticated()
                        ->post(route('team.propose',['language'=>'de']),[
                          'selSize:'.$league->id.':'.$team->id => 1,
                          'club_id' => $club->id,
                          'gperday' => 1,
                          'optmode' => 'min'
                        ]);
      $response->assertStatus(200)
               ->assertSessionHasNoErrors();

     }
     /**
      * list_chart
      *
      * @test
      * @group team
      * @group controller
      *
      * @return void
      */
     public function list_chart()
     {
       //$this->withoutExceptionHandling();
       // create data:  1 club with 5 teams assigned to 1 league each
       $club = Club::where('name','testteamclub')->first();
       $league = League::where('name','testleague')->first();
       $team = $club->teams->first();

       $response = $this->authenticated()
                         ->post(route('team.list-chart',['language'=>'de']),[
                           'selSize:'.$league->id.':'.$team->id => 1,
                           'club_id' => $club->id,
                           'gperday' => 1,
                           'optmode' => 'min'
                         ]);

       $response->assertStatus(200)
                ->assertSessionHasNoErrors();
      }
      /**
       * list_pivot
       *
       * @test
       * @group team
       * @group controller
       *
       * @return void
       */
      public function list_pivot()
      {
        //$this->withoutExceptionHandling();
        // create data:  1 club with 5 teams assigned to 1 league each
        $club = Club::where('name','testteamclub')->first();
        $league = League::where('name','testleague')->first();
        $team = $club->teams->first();

        $response = $this->authenticated()
                          ->post(route('team.list-piv',['language'=>'de']),[
                            'selSize:'.$league->id.':'.$team->id => 1,
                            'club_id' => $club->id,
                            'gperday' => 1,
                            'optmode' => 'min'
                          ]);

        $response->assertStatus(200)
                 ->assertSessionHasNoErrors();
       }
       /**
        * pick_char
        *
        * @test
        * @group team
        * @group controller
        *
        * @return void
        */
       public function pick_char()
       {
         //$this->withoutExceptionHandling();
         // create data:  1 club with 5 teams assigned to 1 league each
         $club = Club::where('name','testteamclub')->first();
         $league = League::where('name','testleague')->first();
         $team = $club->teams->first();

         $response = $this->authenticated()
                           ->post(route('league.team.pickchar',['league'=>$league]),[
                             'team_id' => $team->id,
                             'league_no' => 2,
                           ]);

         $response->assertStatus(200)
                  ->assertSessionHasNoErrors()
                  ->assertJson(['success'=>'all good']);
        }
        /**
         * db_cleanup
         *
         * @test
         * @group team
         * @group controller
         *
         * @return void
         */
       public function db_cleanup()
       {
            /// clean up DB
            $club = Club::where('name','testteamclub')->first();
            $club->teams()->delete();
            $club->leagues()->detach();
            $club->delete();
            $league = League::where('name','testleague')->delete();
            $this->assertDatabaseCount('clubs', 0)
                 ->assertDatabaseCount('teams', 0)
                 ->assertDatabaseCount('leagues', 0);
       }
}
