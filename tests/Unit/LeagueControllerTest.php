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

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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

        $response = $this->authenticated()
            ->get(route('league.create', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('league.league_new')
            ->assertViewHas('region', $this->region)
            ->assertViewHas('agetype', LeagueAgeType::getInstances())
            ->assertViewHas('gendertype', LeagueGenderType::getInstances());
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
        $response = $this->authenticated()
            ->post(route('league.store', ['region' => $this->region]), [
                'shortname' => 'testtoolong',
                'name' => 'testleague',
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
        $schedule = Schedule::where('region_id', $this->region->id)->first();

        $response = $this->authenticated()
            ->post(route('league.store', ['region' => $this->region]), [
                'shortname' => 'TEST',
                'name' => 'testleague',
                'schedule_id' => $schedule->id,
                'league_size_id' => $schedule->league_size->id,
                'age_type' => LeagueAgeType::getRandomValue(),
                'gender_type' => LeagueGenderType::getRandomValue(),
                'above_region' => False
            ]);
        $response->assertRedirect(route('league.index', ['language' => 'de', 'region' => $this->region]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('leagues', ['name' => 'testleague']);

        // rollback
        League::where('name','testleague')->first()->delete();
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
        $response = $this->authenticated()
            ->get(route('league.edit', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200)
            ->assertViewIs('league.league_edit')
            ->assertViewHas('league', $this->testleague)
            ->assertViewHas('agetype', LeagueAgeType::getInstances())
            ->assertViewHas('gendertype', LeagueGenderType::getInstances());
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
        $response = $this->authenticated()
            ->put(route('league.update', ['league' => $this->testleague]), [
                'name' => 'testleague2',
                'shortname' => $this->testleague->shortname,
                'region_id' => $this->testleague->region_id,
                'schedule_id' => 100,
                'age_type' => $this->testleague->age_type,
                'gender_type' => 200,
                'above_region' => False
            ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['schedule_id', 'gender_type']);;
        //$response->dumpSession();
        $this->assertDatabaseMissing('leagues', ['name' => 'testleague2']);
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
        $response = $this->authenticated()
            ->put(route('league.update', ['league' => $this->testleague]), [
                'name' => 'testleague2',
                'shortname' => $this->testleague->shortname,
                'region_id' => $this->testleague->region_id,
                'schedule_id' => $this->testleague->schedule_id,
                'league_size_id' => $this->testleague->league_size_id,
                'age_type' => $this->testleague->age_type,
                'gender_type' => $this->testleague->gender_type,
                'above_region' => False
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors()
            ->assertHeader('Location', route('league.dashboard', ['language' => 'de', 'league' => $this->testleague]));

        $this->assertDatabaseHas('leagues', ['name' => 'testleague2']);
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
        $response = $this->authenticated()
            ->get(route('league.index', ['language' => 'de', 'region' => $this->region]));

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

        // base level region
        $response = $this->authenticated()
            ->get(route('league.list', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200);

        // top level region
        $response = $this->authenticated()
            ->get(route('league.list', ['language' => 'de', 'region' => $this->region->parentRegion]));

        $response->assertStatus(200);
    }
    /**
     * index_mgmt
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function index_mgmt()
    {
        $response = $this->authenticated()
            ->get(route('league.index_mgmt', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('league.league_list_mgmt');
    }
    /**
     * list_mgmt
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function list_mgmt()
    {

        // base level region
        $response = $this->authenticated()
            ->get(route('league.list_mgmt', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200);

        // top level region
        $response = $this->authenticated()
            ->get(route('league.list_mgmt', ['language' => 'de', 'region' => $this->region->parentRegion]));

        $response->assertStatus(200);
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
        $response = $this->authenticated()
            ->get(route(
                'league.dashboard',
                ['language' => 'de', 'league' => $this->testleague]
            ));


        $response->assertStatus(200);
        $response->assertViewIs('league.league_dashboard');
        $response->assertViewHas('league', $this->testleague)
            ->assertViewHas('members')
            ->assertViewHas('games');
    }
    /**
     * briefing
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function briefing()
    {
        $response = $this->authenticated()
            ->get(route('league.briefing', ['language' => 'de', 'league' => $this->testleague]));

        $this->testleague->refresh();
        $response->assertStatus(200)
            ->assertViewIs('league.league_briefing')
            ->assertViewHas('league', $this->testleague)
            ->assertViewHas('clubs')
            ->assertViewHas('teams');
    }
        /**
     * team_dt
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function team_dt()
    {

        // base level region
        $response = $this->authenticated()
            ->get(route('league.team.dt', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200);

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
        $response = $this->authenticated()
            ->get(route('league.sb.region', ['region' => $this->region]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJson([['id' => $this->testleague->id, 'text' => $this->testleague->shortname]]);
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
        $response = $this->authenticated()
            ->get(route('league.sb_freechar', ['league' => $this->testleague]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJson([]);
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
        $this->testleague = $this->region->leagues()->first();
        $response = $this->authenticated()
            ->get(route('league.sb.club', ['league' => $this->testleague]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJson([]);
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
        $response = $this->authenticated()
            ->delete(route('league.destroy', ['league' => $this->testleague]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('leagues', ['id' => $this->testleague->id]);
    }

}
