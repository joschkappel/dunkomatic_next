<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\Team;
use App\Models\League;

use Tests\TestCase;
use Tests\Support\Authentication;

class TeamControllerTest extends TestCase
{
    use Authentication;

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

        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->get(route('league.team.sb', ['league' => $this->testleague]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $team->id, 'text' => $this->testclub_assigned->shortname . $team->team_no]]);
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
        // add 3 more teams
        Team::factory()->count(3)->create(['club_id' => $this->testclub_assigned->id]);
        $team = $this->region->teams()->whereNull('league_id')->with('club')->first();

        $response = $this->authenticated()
            ->get(route('team.free.sb', ['league' => $this->testleague]));

        //$response->dump();
        if ($team->count() > 0) {
            $response->assertStatus(200)
                ->assertJsonFragment([['id' => $team->id, 'text' => $team->namedesc]]);
        } else {
            $response->assertStatus(200)
                ->assertJson([[]]);
        }
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.inject', ['league' => $this->testleague]), [
                'team_id' => $team->id,
                'league_no' => 1,
            ]);

        // $response->dumpSession();
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $this->testleague->id]);
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
        $response = $this->authenticated()
            ->get(route('team.plan-leagues', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('team.teamleague_dashboard')
            ->assertViewHas('club', $this->testclub_assigned);
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('team.store-plan'), [
                'selSize:' . $this->testleague->id . ':' . $team->id => 1,
            ]);

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => $this->testleague->id]);
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('team.propose', ['language' => 'de']), [
                'selSize:' . $this->testleague->id . ':' . $team->id => 1,
                'club_id' => $this->testclub_assigned->id,
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('team.list-chart', ['language' => 'de']), [
                'selSize:' . $this->testleague->id . ':' . $team->id => 1
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('team.list-piv', ['language' => 'de']), [
                'selSize:' . $this->testleague->id . ':' . $team->id => 1,
                'club_id' => $this->testclub_assigned->id,
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
        $team = $this->testclub_assigned->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => $this->testleague]), [
                'team_id' => $team->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);
    }
}
