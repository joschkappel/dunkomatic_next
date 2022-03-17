<?php

namespace Tests\Unit;

use App\Models\League;
use App\Traits\LeagueFSM;
use Tests\TestCase;
use Tests\Support\Authentication;

class CalendarControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    /**
     * cal_league
     *
     * @test
     * @group league
     * @group controller
     *
     * @return void
     */
    public function cal_league()
    {

        $league = League::factory()->selected(4, 4)->create();
        $response = $this->authenticated()
            ->get(route('cal.league', ['language' => 'de', 'league' => $league]));

        $response->assertStatus(404);

        $this->close_selection($league);
        $this->close_freeze($league);
        $response = $this->authenticated()
            ->get(route('cal.league', ['language' => 'de', 'league' => $league]));

        $response->assertStatus(200)
            ->assertDownload($league->region->code . '-' . $league->shortname . '.ics');

        $this->destroy($league);
    }
    /**
     * cal_club
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function cal_club()
    {

        $league = League::factory()->selected(4, 4)->create();
        $club = $league->clubs()->first();

        $response = $this->authenticated()
            ->get(route('cal.club', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(404);

        $this->close_selection($league);
        $this->close_freeze($league);

        $response = $this->authenticated()
            ->get(route('cal.club', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(200)
            ->assertDownload($club->region->code . '-' . $club->shortname . '.ics');

        $this->destroy($league);
    }

    /**
     * cal_club_home
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function cal_club_home()
    {

        $league = League::factory()->selected(4, 4)->create();
        $club = $league->clubs()->first();

        $response = $this->authenticated()
            ->get(route('cal.club.home', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(404);

        $this->close_selection($league);
        $this->close_freeze($league);

        $response = $this->authenticated()
            ->get(route('cal.club.home', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(200)
            ->assertDownload($club->region->code . '-' . $club->shortname . '_home.ics');

        $this->destroy($league);
    }
    /**
     * cal_club_referee
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function cal_club_referee()
    {

        $league = League::factory()->selected(4, 4)->create();
        $club = $league->clubs()->first();

        $response = $this->authenticated()
            ->get(route('cal.club.referee', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(404);

        $this->close_selection($league);
        $this->close_freeze($league);
        $game = $league->games()->first();
        $game->update(['referee_1' => $club->shortname]);

        $response = $this->authenticated()
            ->get(route('cal.club.referee', ['language' => 'de', 'club' => $club]));

        $response->assertStatus(200)
            ->assertDownload($club->region->code . '-' . $club->shortname . '_referee.ics');

        $this->destroy($league);
    }

    public function destroy(League $league)
    {
        $clubs = $league->clubs;
        $league->clubs()->detach();
        $league->games()->delete();
        $league->teams()->delete();
        foreach ($clubs as $c) {
            $c->gyms()->delete();
            $members = $c->members;
            $c->members()->detach();
            foreach ($members as $m){
                $m->delete();
            }
            $c->delete();
        };
        $league->delete();
        $league->schedule()->delete();
    }
}
