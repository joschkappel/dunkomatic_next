<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use App\Models\League;
use App\Traits\LeagueFSM;
use Tests\Support\Authentication;
use Tests\TestCase;

class ClubControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
            ->assertSessionHasErrors(['shortname', 'club_no']);

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
        $response = $this->authenticated()
            ->withSession(['cur_region' => $this->region])
            ->get(route('club.edit', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('club.club_edit')
            ->assertViewHas('club', $this->testclub_assigned);
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
        $response = $this->authenticated()
            ->withSession(['cur_region' => $this->region])
            ->put(route('club.update', ['club' => $this->testclub_assigned]), [
                'name' => 'testclub2',
                'shortname' => 'TEST',
                'url' => 'anyurl',
                'club_no' => '12345',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['url']);
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
        $response = $this->authenticated()
            ->put(route('club.update', ['club' => $this->testclub_assigned]), [
                'name' => 'testclub2',
                'shortname' => 'TEST',
                'url' => $this->testclub_assigned->url,
                'club_no' => $this->testclub_assigned->club_no,
            ]);
        $this->testclub_assigned->refresh();
        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('club.dashboard', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $this->assertDatabaseHas('clubs', ['name' => 'testclub2']);
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

        $clubs = array_fill(0, Club::count(), 0);
        $response->assertStatus(200)
            ->assertJsonPath('data.*.games_home_count', $clubs);

        // test top level region
        $response = $this->authenticated()
            ->get(route('club.list', ['region' => $this->region->parentRegion]));

        $response->assertStatus(200)
            ->assertJsonPath('data.*.games_home_count', $clubs);
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
        // runwith league in selected state
        $response = $this->authenticated()
            ->get(route('club.team.dt', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertJsonFragment(['team_no' => $this->testclub_assigned->teams->first()->team_no]);

        // rerun with leagu in assigned
        $this->reopen_team_registration($this->testleague);
        $response = $this->authenticated()
            ->get(route('club.team.dt', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertJsonFragment(['team_no' => $this->testclub_assigned->teams->first()->team_no]);
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
        $club = $this->testclub_assigned;  // $this->region->clubs()->first();
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
            ->assertJsonFragment([['id' => $club->id, 'text' => '('.$club->region->code.') '.$club->shortname]]);
    }

    /**
     * sb_league
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function sb_league()
    {
        // test with league in not assigned state
        $response = $this->authenticated()
            ->get(route('club.sb.league', ['club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertJsonFragment([]);

        // move league back to assgined state and rerun
        $this->reopen_team_registration($this->testleague);

        $response = $this->authenticated()
            ->get(route('club.sb.league', ['club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $this->testleague->id, 'text' => $this->testleague->shortname]]);
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
        $response = $this->authenticated()
            ->delete(route('club.destroy', ['club' => $this->testclub_free]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('clubs', ['id' => $this->testclub_free->id]);
        $this->assertDatabaseCount('clubs', 3);
    }
}
