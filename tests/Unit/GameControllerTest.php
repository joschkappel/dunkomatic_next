<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Support\Authentication;

class GameControllerTest extends TestCase
{
    use Authentication;

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
            ->get(route('game.datatable', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200);

    }

}
