<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Schedule;
use App\Models\Club;

use App\Enums\LeagueAgeType;
use App\Enums\LeagueGenderType;

use Tests\TestCase;
use Tests\Support\Authentication;

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
                          'above_region' => False
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['shortname']);

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
                          'league_size_id' => $schedule->league_size->id,
                          'region_id' => $this->region->id,
                          'age_type' => LeagueAgeType::getRandomValue(),
                          'gender_type' => LeagueGenderType::getRandomValue(),
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
                          'league_size_id' => $league->league_size_id,
                          'region_id' => $this->region->id,
                          'age_type' => $league->age_type,
                          'gender_type' => $league->gender_type,
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
      $leagues = $this->region->leagues()
                         ->with('schedule.league_size')
                         ->withCount(['clubs','teams','games',
                                      'games_notime','games_noshow'])
                         ->get();

      $response = $this->authenticated( )
                        ->get(route('league.list',['language'=> 'de', 'region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200);
      $response->assertJsonPath('data.*.clubs_count', [0])
               ->assertJsonPath('data.*.teams_count', [0])
               ->assertJsonPath('data.*.games_count', [0]);
               //->assertJsonPath('data.*.name', ['testleague2']);
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
                         ->get(route('club.sb.league',['club'=>$club]));

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
                          ->post(route('league.assign-clubs',['league'=>$league]),[
                            'club_id' => $club->id
                          ]);

        $response->assertStatus(302)
                 ->assertSessionHasNoErrors();

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

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();
      $this->assertDatabaseMissing('leagues', ['id'=>$league->id]);
    }
    /**
     * db_cleanup
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
   public function db_cleanup()
   {
        /// clean up DB
        Club::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0)
             ->assertDatabaseCount('clubs', 0);
   }
}
