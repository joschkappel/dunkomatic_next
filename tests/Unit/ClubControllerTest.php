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
            ->get(route('club.create', ['language' => 'de', 'region' => $this->region]));

        // $response->dump();
        $response->assertStatus(200)
            ->assertViewIs('club.club_new')
            ->assertViewHas('region', $this->region);
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
            ->post(route('club.store', ['region' => $this->region]), [
                'shortname' => 'testtoolong',
                'name' => 'testclub',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['shortname', 'url', 'club_no']);

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
            ->post(route('club.store', ['region' => $this->region]), [
                'shortname' => 'TEST',
                'name' => 'testclub',
                'club_no' => '9999',
                'url' => 'http://example.com',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('club.index', ['language' => 'de', 'region' => $this->region]));

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
        $club = Club::where('name', 'testclub')->first();

        $response = $this->authenticated()
            ->withSession(['cur_region' => $this->region])
            ->get(route('club.edit', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(200)
            ->assertViewIs('club.club_edit')
            ->assertViewHas('club', $club);
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
        $club = Club::where('name', 'testclub')->first();
        $response = $this->authenticated()
            ->withSession(['cur_region' => $this->region])
            ->put(route('club.update', ['club' => $club]), [
                'name' => 'testclub2',
                'shortname' => $club->shortname,
                'url' => 'anyurl',
                'club_no' => $club->club_no
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['url']);;
        //$response->dumpSession();
        $this->assertDatabaseMissing('clubs', ['name' => 'testclub2']);
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
        $club = Club::where('name', 'testclub')->first();
        $response = $this->authenticated()
            ->put(route('club.update', ['club' => $club]), [
                'name' => 'testclub2',
                'shortname' => $club->shortname,
                'url' => $club->url,
                'club_no' => $club->club_no
            ]);
        $club->refresh();
        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('club.dashboard', ['language' => 'de', 'club' => $club]));

        $this->assertDatabaseHas('clubs', ['name' => $club->name]);
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
            ->get(route('club.index', ['language' => 'de', 'region' => $this->region]));

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
        // test base level region
        $response = $this->authenticated()
            ->get(route('club.list', ['region' => $this->region]));

        $response->assertStatus(200)
            ->assertJsonPath('data.*.games_home_count', [0]);

        // test top level region
        $response = $this->authenticated()
            ->get(route('club.list', ['region' => $this->region->parentRegion]));

        $response->assertStatus(200)
            ->assertJsonPath('data.*.games_home_count', [0]);
    }
    /**
     * team_dt
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function team_dt()
    {
        $club = $this->region->clubs()->first();
        $response = $this->authenticated()
            ->get(route('club.team.dt', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(200)
            ->assertJsonPath('data.*.team', $club->teams->toArray());

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
            ->get(route('club.dashboard', ['language' => 'de', 'club' => $club]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertViewIs('club.club_dashboard')
            ->assertViewHas('club', $club)
            ->assertViewHas('gyms', $club->gyms()->get());
    }
    /**
     * briefing
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function briefing()
    {
        $club = $this->region->clubs()->first();
        $response = $this->authenticated()
            ->get(route('club.briefing', ['language' => 'de', 'club' => $club]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertViewIs('club.club_briefing')
            ->assertViewHas('club', $club)
            ->assertViewHas('gyms', $club->gyms()->get())
            ->assertViewHas('teams', $club->teams()->get());
    }
    /**
     * list_homegame
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function list_homegame()
    {
        $club = $this->region->clubs()->first();
        $response = $this->authenticated()
            ->get(route('club.list.homegame', ['language' => 'de', 'club' => $club]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertViewIs('game.gamehome_list')
            ->assertViewHas('club', $club);
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
        // base level region
        $club = $this->region->clubs()->first();
        $response = $this->authenticated()
            ->get(route('club.sb.region', ['region' => $this->region]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $club->id, 'text' => $club->shortname]]);

        // top level region
        $tregion = $this->region->parentRegion;
        $response = $this->authenticated()
            ->get(route('club.sb.region', ['region' => $tregion]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $club->id, 'text' => '(' . $club->region->code . ') ' . $club->shortname]]);
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
        $club = Club::where('name', 'testclub2')->first();
        $response = $this->authenticated()
            ->delete(route('club.destroy', ['club' => $club]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('clubs', ['id' => $club->id]);
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
