<?php

namespace Tests\Unit;

use Tests\Support\Authentication;
use Tests\TestCase;

class RegionGameControllerTest extends TestCase
{
    use Authentication;

    /**
     * upload
     *
     * @test
     * @group region
     * @group game
     * @group controller
     *
     * @return void
     */
    public function upload()
    {
        $response = $this->authenticated()
            ->get(route('region.upload.game', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('game.game_file_upload')
            ->assertViewHas('context', 'referee');
    }
}
