<?php

namespace Tests\Feature;

use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use Authentication;

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
     * index
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function index()
    {
        $response = $this->authenticated()
            ->get(route('game.index', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('game.game_list')
            ->assertViewHas('region', $this->region);
    }

    /**
     * datatable
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        $response = $this->authenticated()
            ->get(route('game.datatable', ['language' => 'de', 'region' => $this->testleague->region]));

        $response->assertStatus(200);
    }
}
