<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Schedule;
use App\Models\Club;
use App\Models\Team;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class LeagueControllerTest extends TestCase
{
    use Authentication;

    /**
     * create
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function create()
    {

      $response = $this->authenticated( )
                        ->get(route('league.create',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('league.league_new')
               ->assertViewHas('region',$this->region)
               ->assertViewHas('agetype',LeagueAgeType::getInstances())
               ->assertViewHas('gendertype',LeagueGenderType::getInstances());

    }
    /**
     * store NOT OK
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
      $response = $this->authenticated( )
                        ->post(route('league.store'), [
                          'shortname' => 'testtoolong',
                          'name' => 'testleague',
                          'region_id' => $this->region->id,
                          'age_type' => LeagueAgeType::getRandomValue(),
                          'gender_type' => LeagueGenderType::getRandomValue(),
                          'active' => True,
                          'above_region' => False
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['shortname','schedule_id']);

      $this->assertDatabaseMissing('leagues', ['name' => 'testleague']);

    }

    /**
     * store OK
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
      Schedule::factory()->count(3)->create();
      $schedule = Schedule::where('region_id',$this->region->id)->first();

      $response = $this->authenticated( )
                        ->post(route('league.store'), [
                          'shortname' => 'TEST',
                          'name' => 'testleague',
                          'schedule_id' => $schedule->id,
                          'region_id' => $this->region->id,
                          'age_type' => LeagueAgeType::getRandomValue(),
                          'gender_type' => LeagueGenderType::getRandomValue(),
                          'active' => True,
                          'above_region' => False
                      ]);
      $response->assertRedirect(route('league.index', ['language'=>'de']))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('leagues', ['name' => 'testleague']);

    }
    /**
     * edit
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      //$this->withoutExceptionHandling();
      $league = League::where('name','testleague')->first();

      $response = $this->authenticated( )
                        ->get(route('league.edit',['language'=>'de', 'league'=>$league]));

      $response->assertStatus(200)
               ->assertViewIs('league.league_edit')
               ->assertViewHas('league',$league)
               ->assertViewHas('agetype',LeagueAgeType::getInstances())
               ->assertViewHas('gendertype',LeagueGenderType::getInstances());
    }
    /**
     * update not OK
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
      //$this->withoutExceptionHandling();
      $league = League::where('name','testleague')->first();
      $response = $this->authenticated( )
                        ->put(route('league.update',['league'=>$league]),[
                          'name' => 'testleague2',
                          'shortname' => $league->shortname,
                          'region_id' => $league->region_id,
                          'schedule_id' => 100,
                          'region_id' => $this->region->id,
                          'age_type' => $league->age_type,
                          'gender_type' => 200,
                          'active' => True,
                          'above_region' => False
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['schedule_id','gender_type']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('leagues', ['name'=>'testleague2']);
    }
    /**
     * update OK
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
      //$this->withoutExceptionHandling();
      $league = League::where('name','testleague')->first();
      $response = $this->authenticated( )
                        ->put(route('league.update',['league'=>$league]),[
                          'name' => 'testleague2',
                          'shortname' => $league->shortname,
                          'region_id' => $league->region_id,
                          'schedule_id' => $league->schedule_id,
                          'region_id' => $this->region->id,
                          'age_type' => $league->age_type,
                          'gender_type' => $league->gender_type,
                          'active' => True,
                          'above_region' => False
                        ]);

      $league->refresh();
      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('league.dashboard',['language'=>'de', 'league'=>$league]));

      $this->assertDatabaseHas('leagues', ['name'=>$league->name]);
    }
    /**
     * index
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function index()
    {
      $response = $this->authenticated( )
                        ->get(route('league.index',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('league.league_list');

    }
    /**
     * list
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function list()
    {
      $response = $this->authenticated( )
                       ->get(route('league.list',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJsonPath('data.*.name', ['testleague2']);
    }
    /**
     * index_stats
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function index_stats()
    {
      $response = $this->authenticated( )
                        ->get(route('league.index_stats',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('league.league_stats');

    }
    /**
     * list_stats
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function list_stats()
    {
      $leagues = $this->region->leagues()
                         ->with('schedule.league_size')
                         ->withCount(['clubs','teams','games',
                                      'games_notime','games_noshow'])
                         ->get();

      $response = $this->authenticated( )
                        ->get(route('league.list_stats',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200);
      $response->assertJsonPath('data.*.clubs_count', [0])
               ->assertJsonPath('data.*.teams_count', [0])
               ->assertJsonPath('data.*.games_count', [0])
               ->assertJsonPath('data.*.name', ['testleague2']);
    }
    /**
     * dashboard
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function dashboard()
    {
      $league = $this->region->leagues()->first();
      $response = $this->authenticated( )
                        ->get(route('league.dashboard',
                               ['language'=>'de', 'league'=>$league]));


      $response->assertStatus(200);
      $response->assertViewIs('league.league_dashboard');
      $response->assertViewHas('league',$league)
               ->assertViewHas('clubs')
               ->assertViewHas('members')
               ->assertViewHas('games');
    }
    /**
     * sb_region
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function sb_region()
    {
      $league = $this->region->leagues()->first();
      $response = $this->authenticated( )
                        ->get(route('league.sb.region',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJson([['id'=>$league->id,'text'=>$league->shortname]]);
     }
     /**
      * sb_freechars
      *
      * @test
      * @group league
      * @group controller
      *
      * @return void
      */
     public function sb_freechars()
     {
       $league = $this->region->leagues()->first();
       $response = $this->authenticated( )
                         ->get(route('league.sb_freechar',['league'=>$league]));

       //$response->dump();
       $response->assertStatus(200)
                ->assertJson([['id'=>'1','text'=>'1 - A'],['id'=>'2','text'=>'2 - B']]);
      }
     /**
      * sb_club
      *
      * @test
      * @group league
      * @group controller
      *
      * @return void
      */
     public function sb_club()
     {
       Club::factory()->count(3)->create();
       $club = $this->region->clubs()->first();
       $response = $this->authenticated( )
                         ->get(route('league.sb.club',['club'=>$club]));

       //$response->dump();
       $response->assertStatus(200)
                ->assertJson([]);
      }
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

        $league = $this->region->leagues()->first();
        $club = $this->region->clubs()->first();

        $response = $this->authenticated( )
                          ->post(route('league.assign-club',['league'=>$league]),[
                            'item_id' => 1,
                            'club_id' => $club->id
                          ]);

        $response->assertStatus(302)
                 ->assertSessionHasNoErrors()
                 ->assertHeader('Location', route('league.dashboard',['language'=>'de', 'league'=>$league]));

        $this->assertDatabaseHas('club_league', ['club_id'=>$club->id,'league_id'=>$league->id,'league_no'=>1]);

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
        //$this->withoutExceptionHandling();
        $league = $this->region->leagues()->first();
        $club = $league->clubs()->first();

        $response = $this->authenticated( )
                          ->delete(route('league.deassign-club',['league'=>$league, 'club'=>$club]));

        $response->assertStatus(200)
                 ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('club_league', ['club_id'=>$club->id,'league_id'=>$league->id,'league_no'=>1]);

      }
    /**
     * destroy
     *
     * @test
     * @group league
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
      //$this->withoutExceptionHandling();
      $league = League::where('name','testleague2')->first();
      $response = $this->authenticated( )
                        ->delete(route('league.destroy',['league'=>$league]));

      $response->assertStatus(200)
               ->assertSessionHasNoErrors();
      $this->assertDatabaseMissing('leagues', ['id'=>$league->id]);
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
        $this->assertDatabaseCount('leagues', 0);
   }
}
