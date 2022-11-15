<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class ClubTeamControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * pickchar
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function pickchar()
    {
        $response = $this->authenticated()
                          ->get(route('club.team.pickchar', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
                 ->assertViewIs('club.club_pickchar')
                 ->assertViewHas('club', $this->testclub_assigned);
    }

    /**
     * league_char_dt
     *
     * @test
     * @group team
     * @group controller
     *
     * @return void
     */
    public function league_char_dt()
    {
        $response = $this->authenticated()
                          ->get(route('club.league_char.dt', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200);
    }

    /**
     * destroy
     *
     * @test
     * @group team
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        $team = $this->testclub_assigned->teams->first();
        $response = $this->authenticated()
                          ->delete(route('team.destroy', ['team' => $team]));

        $response->assertStatus(302)
                 ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
    }
}
