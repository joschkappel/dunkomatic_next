<?php

namespace Tests\Unit;

use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;

class ClubControllerTest extends TestCase
{
    use Authentication;

    /**
     * create
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function create()
    {

      $response = $this->authenticated()
                        ->get(route('club.create',['language'=>'de']));

      // $response->dump();
      $response->assertStatus(200)
               ->assertViewIs('club.club_new')
               ->assertViewHas('region',$this->region);

    }
    /**
     * store NOT OK
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
      $response = $this->authenticated()
                        ->post(route('club.store'), [
                          'shortname' => 'testtoolong',
                          'name' => 'testclub',
                          'region' => $this->region->code,
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['shortname','url','club_no']);

      $this->assertDatabaseMissing('clubs', ['name' => 'testclub']);

    }

    /**
     * store OK
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {

      $response = $this->authenticated()
                        ->post(route('club.store'), [
                          'shortname' => 'TEST',
                          'name' => 'testclub',
                          'region' => $this->region->code,
                          'club_no' => '9999',
                          'url' => 'http://example.com',
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors()
          ->assertHeader('Location', route('club.index', ['language'=>'de']));

      $this->assertDatabaseHas('clubs', ['name' => 'testclub']);

    }
    /**
     * edit
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();

      $response = $this->authenticated()
                        ->get(route('club.edit',['language'=>'de', 'club'=>$club]));

      $response->assertStatus(200)
               ->assertViewIs('club.club_edit')
               ->assertViewHas('club',$club);
    }
    /**
     * update not OK
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $response = $this->authenticated()
                        ->put(route('club.update',['club'=>$club]),[
                          'name' => 'testclub2',
                          'shortname' => $club->shortname,
                          'url' => 'anyurl',
                          'region' => $this->region->code,
                          'club_no' => $club->club_no
                        ]);

      $response->assertStatus(302)
               ->assertSessionHasErrors(['url']);;
      //$response->dumpSession();
      $this->assertDatabaseMissing('clubs', ['name'=>'testclub2']);
    }
    /**
     * update OK
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub')->first();
      $response = $this->authenticated()
                        ->put(route('club.update',['club'=>$club]),[
                          'name' => 'testclub2',
                          'shortname' => $club->shortname,
                          'url' => $club->url,
                          'region' => $this->region->code,
                          'club_no' => $club->club_no
                        ]);
      $club->refresh();
      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertHeader('Location', route('club.dashboard',['language'=>'de', 'club'=>$club]));

      $this->assertDatabaseHas('clubs', ['name'=>$club->name]);
    }
    /**
     * index
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function index()
    {

      $response = $this->authenticated()
                        ->get(route('club.index',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('club.club_list');

    }
    /**
     * list
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function list()
    {
      $response = $this->authenticated()
                        ->get(route('club.list',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJsonPath('data.*.name', ['testclub2']);
    }
    /**
     * index_stats
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function index_stats()
    {
      $response = $this->authenticated()
                        ->get(route('club.index_stats',['language'=>'de']));

      $response->assertStatus(200)
               ->assertViewIs('club.club_stats');

    }
    /**
     * list_stats
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function list_stats()
    {
      $clubs = $this->region->clubs()->withCount(['leagues','teams','games_home',
                                     'games_home_notime','games_home_noshow'
                          ])
                        ->orderBy('shortname','ASC')
                        ->get();
      $response = $this->authenticated()
                        ->get(route('club.list_stats',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJsonPath('data.*.games_home_count', [0])
               ->assertJsonPath('data.*.name', ['testclub2']);
    }
    /**
     * dashboard
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function dashboard()
    {
      $club = $this->region->clubs()->first();
      $response = $this->authenticated()
                        ->get(route('club.dashboard',['language'=>'de', 'club'=>$club]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertViewIs('club.club_dashboard')
               ->assertViewHas('club',$club)
               ->assertViewHas('gyms',$club->gyms()->get())
               ->assertViewHas('teams',$club->teams()->get());
    }
    /**
     * sb_region
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function sb_region()
    {
      $club = $this->region->clubs()->first();
      $response = $this->authenticated()
                        ->get(route('club.sb.region',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJsonFragment([['id'=>$club->id,'text'=>$club->shortname]]);
     }
    /**
     * destroy
     *
     * @test
     * @group club
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
      //$this->withoutExceptionHandling();
      $club = Club::where('name','testclub2')->first();
      $response = $this->authenticated()
                        ->delete(route('club.destroy',['club'=>$club]));

      $response->assertStatus(200)
               ->assertSessionHasNoErrors();
      $this->assertDatabaseMissing('clubs', ['id'=>$club->id]);
    }
    /**
     * db_cleanup
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
   public function db_cleanup()
   {
        /// clean up DB
        $this->assertDatabaseCount('clubs', 0);
   }
}
