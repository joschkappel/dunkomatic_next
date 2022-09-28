<?php

namespace Tests\Feature;

use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueSizeControllerTest extends TestCase
{
    use Authentication;

    /**
     * index
     *
     * @test
     * @group scheme
     * @group league
     * @group controller
     *
     * @return void
     */
    public function index()
    {
        $response = $this->authenticated()
                          ->get(route('size.index'));

        //$response->dump();
        $response->assertStatus(200)
                 ->assertJsonFragment([['id' => 4, 'text' => '8 Teams']]);
    }
}
