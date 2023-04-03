<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use App\Models\League;
use App\Traits\LeagueFSM;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Authentication;
use Tests\TestCase;

class ClubGameControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * chart
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function chart()
    {
        $response = $this->authenticated()
            ->get(route('club.game.chart', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('club.club_hgame_chart')
            ->assertViewHas('club', $this->testclub_assigned);
    }

    /**
     * list_Home
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function list_home()
    {
        $response = $this->authenticated()
            ->get(route('club.game.list_home', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200);
    }

    /**
     * chart_Home
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function chart_home()
    {
        $response = $this->authenticated()
            ->get(route('club.game.chart_home', ['club' => $this->testclub_assigned]));

        $response->assertStatus(200);
    }

}
