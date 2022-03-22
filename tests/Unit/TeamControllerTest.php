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

        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->get(route('league.team.sb', ['league' => static::$testleague]));

        //$response->dump();
        $response->assertStatus(200)
            ->assertJsonFragment([['id' => $team->id, 'text' => static::$testclub->shortname . $team->team_no]]);
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
        Team::factory()->count(3)->create(['club_id' => static::$testclub->id]);
        $team = $this->region->teams()->whereNull('league_id')->with('club')->first();

        $response = $this->authenticated()
            ->get(route('team.free.sb', ['league' => static::$testleague]));

        //$response->dump();
        if ($team->count() > 0) {
            $response->assertStatus(200)
                ->assertJsonFragment([['id' => $team->id, 'text' => $team->club->shortname . $team->team_no . ' (' . $team->league_prev . ')']]);
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.inject', ['league' => static::$testleague]), [
                'team_id' => $team->id,
                'league_no' => 1,
            ]);

        // $response->dumpSession();
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => static::$testleague->id]);
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
            ->get(route('team.plan-leagues', ['language' => 'de', 'club' => static::$testclub]));

        $response->assertStatus(200)
            ->assertViewIs('team.teamleague_dashboard')
            ->assertViewHas('club', static::$testclub);
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('team.store-plan'), [
                'selSize:' . static::$testleague->id . ':' . $team->id => 1,
            ]);

        $response
            ->assertStatus(200)
            ->assertSessionHasNoErrors();
        //$response->dump();
        $this->assertDatabaseHas('teams', ['league_no' => 1, 'league_id' => static::$testleague->id]);
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('team.propose', ['language' => 'de']), [
                'selSize:' . static::$testleague->id . ':' . $team->id => 1,
                'club_id' => static::$testclub->id,
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('team.list-chart', ['language' => 'de']), [
                'selSize:' . static::$testleague->id . ':' . $team->id => 1
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('team.list-piv', ['language' => 'de']), [
                'selSize:' . static::$testleague->id . ':' . $team->id => 1,
                'club_id' => static::$testclub->id,
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
        $team = static::$testclub->teams->first();

        $response = $this->authenticated()
            ->post(route('league.team.pickchar', ['league' => static::$testleague]), [
                'team_id' => $team->id,
                'league_no' => 2,
            ]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);
    }
}
