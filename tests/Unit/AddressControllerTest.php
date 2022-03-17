<?php

namespace Tests\Unit;

use App\Enums\Role;
use Tests\TestCase;
use Tests\Support\Authentication;

class AddressControllerTest extends TestCase
{
    use Authentication;

    /**
     * index_byrole
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function index_byrole()
    {

        $response = $this->authenticated()
            ->get(route('address.index_byrole', ['language' => 'de', 'region' => $this->region, 'role' => Role::ClubLead]));

        $response->assertStatus(200)
            ->assertViewIs('address.address_role_list')
            ->assertViewHas('region', $this->region)
            ->assertViewHas('role', Role::ClubLead);
    }
    /**
     * inde_byrole_Dtx
     *
     * @test
     * @group controller
     *
     * @return void
     */
    public function index_byrole_dt()
    {

        $response = $this->authenticated()
            ->get(route('address.index_byrole.dt', ['language' => 'de', 'region' => $this->region, 'role' => Role::ClubLead]));

        $response->assertStatus(200);
        $response = $this->authenticated()
            ->get(route('address.index_byrole.dt', ['language' => 'de', 'region' => $this->region, 'role' => Role::LeagueLead]));

        $response->assertStatus(200);
        $response = $this->authenticated()
            ->get(route('address.index_byrole.dt', ['language' => 'de', 'region' => $this->region, 'role' => Role::RegionLead]));

        $response->assertStatus(200);
    }
}
