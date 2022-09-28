<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckResultsControllerTest extends TestCase
{
    /**
     * showVIew
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function showView()
    {
        $response = $this->get('health');

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertViewIs('health::list');

        // do with a refresh
        $response = $this->get('health', ['fresh' => true]);

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertViewIs('health::list');
    }
}
