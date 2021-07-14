<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;
use App\Models\Member;
use App\Models\Game;
use App\Models\Team;
use App\Models\Gym;
use App\Models\Schedule;
use Carbon\Carbon;

use App\Enums\LeagueState;
use App\Enums\LeagueStateChange;
use App\Enums\Role;

use App\Notifications\RegisterTeams;
use App\Notifications\SelectTeamLeagueNo;
use App\Notifications\LeagueGamesGenerated;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;

class LeagueStateControllerTest extends TestCase
{
    use Authentication;

    /**
     * close assignment
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function close_assignment()
    {
      // create data:  1 club with 5 teams assigned to 1 league each
      Club::factory()->hasTeams(2)->hasGyms(1)->hasAttached( Member::factory()->count(1), ['role_id' => Role::ClubLead() ])->count(5)->create();
      $schedule = Schedule::factory()->create(['name'=>'testschedule']);
      $this->authenticated( )
                        ->post(route('schedule_event.store',['schedule'=>$schedule]), [
                          'startdate' => Carbon::now()->addDays(32),
                      ]);

      $league = League::factory()->create(['name'=>'testleague', 'state'=>LeagueState::Assignment(), 'schedule_id'=>$schedule->id]);


      // assign clubs to league
      $clubs = Club::all();
      $league->clubs()->attach( [
          $clubs[0]->id =>  ['league_no'=>1,'league_char'=>'A'],
          $clubs[1]->id =>  ['league_no'=>2,'league_char'=>'B'],
          $clubs[2]->id =>  ['league_no'=>3,'league_char'=>'C'],
          $clubs[3]->id =>  ['league_no'=>4,'league_char'=>'D'],
      ]);

      Notification::fake();
      Notification::assertNothingSent();

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::CloseAssignment()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Registration()]);

       $member = Club::first()->members()->first();
       //  assert club members are notified
        Notification::assertSentTo(
          [$member], RegisterTeams::class
      );

    }

    /**
     * close registration
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function close_registration()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Registration()]);

      Notification::fake();
      Notification::assertNothingSent();

      // register 4 teams
      $teams = Team::inRandomOrder()->limit(4)->get();
      foreach ($teams as $t){
        $t->league()->associate($league)->save();
      }


      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::CloseRegistration()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Selection()]);

       $member = Club::first()->members()->first();
       //  assert club members are notified
        Notification::assertSentTo(
          [$member], SelectTeamLeagueNo::class
      );      

    }

    /**
     * close selection
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function close_selection()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Selection()]);

      // select league ns for  4 teams
      $teams = $league->teams;
      $teams[0]->update(['league_char'=>'A', 'league_no'=>1]);
      $teams[1]->update(['league_char'=>'B', 'league_no'=>2]);
      $teams[2]->update(['league_char'=>'C', 'league_no'=>3]);
      $teams[3]->update(['league_char'=>'D', 'league_no'=>4]);


      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::CloseSelection()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Freeze()]);

    }

    /**
     * generate games
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function generate_games()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Freeze()]);

      Notification::fake();
      Notification::assertNothingSent();

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.game.store',['league'=>$league->id]) );

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Scheduling()])
            ->assertDatabaseHas('games', ['league_id' => $league->id]);

      $member = Club::first()->members()->wherePivot('role_id',Role::ClubLead)->first();
        //  assert club members are notified
      Notification::assertSentTo(
            [$member], LeagueGamesGenerated::class
      );   

    }    

    /**
     * close scheduling
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function close_scheduling()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Scheduling()]);

      Notification::fake();
      Notification::assertNothingSent();


      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::CloseScheduling()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Live()]);

    } 

    /**
     * reopen scheduling
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_scheduling()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Live()]);

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::OpenScheduling()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Scheduling()]);

    }        

   /**
     * reopen freeze
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_freeze()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Scheduling()]);

      // change state to registration
      $response = $this->authenticated( )
                        ->delete(route('league.game.destroy',['league'=>$league->id]));

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Freeze()])
            ->assertDatabaseMissing('games', ['league_id' => $league->id]);

    }       
    
    /**
     * reopen selection
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_selection()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Freeze()]);

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::OpenSelection()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Selection()]);

    }
    /**
     * reopen registration
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_registration()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Selection()]);

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::OpenRegistration()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Registration()]);

    }              

    /**
     * reopen assignment
     *
     * @test
     * @group league
     * @group leaguestate
     * @group controller
     *
     * @return void
     */
    public function backto_assignment()
    {
      $league = League::where('name','testleague')->first();

      $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Registration()]);

      // change state to registration
      $response = $this->authenticated( )
                        ->post(route('league.state.change',['league'=>$league->id]), [
                          'action' => LeagueStateChange::OpenAssignment()
                      ]);

       $response->assertStatus(200);
       $this->assertDatabaseHas('leagues', ['id' => $league->id, 'state' => LeagueState::Assignment()]);

    }       

    /**
     * db_cleanup
     *
     * @test
     * @group league
     * @group leaguestate
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
        foreach( Club::all() as $c){
          $c->leagues()->detach();
          $c->members()->detach();
          $c->delete();
        }
        $league = League::where('name','testleague')->first();
        $league->schedule->events()->delete();
        $league->delete();
        Schedule::whereNotNull('id')->delete();
        //League::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0);
   }
}
