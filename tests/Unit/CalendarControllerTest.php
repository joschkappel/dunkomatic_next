<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Club;
use App\Traits\LeagueFSM;
use Tests\TestCase;
use Tests\Support\Authentication;

class CalendarControllerTest extends TestCase
{
    use Authentication, LeagueFSM;
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

        $league = $this->testleague;
        $response = $this->authenticated()
            ->get(route('cal.league', ['language' => 'de', 'league' => $league]));

        $response->assertStatus(404);

        $this->close_selection($league);
        $this->close_freeze($league);
        $response = $this->authenticated()
            ->get(route('cal.league', ['language' => 'de', 'league' => $league]));

        $response->assertStatus(200)
            ->assertDownload($league->region->code . '-' . $league->shortname . '.ics');


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

        $league = $this->testleague;
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

        $league  = $this->testleague;
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

        $league = $this->testleague;
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


    }

}
