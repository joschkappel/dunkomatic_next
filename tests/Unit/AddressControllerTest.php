<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use Tests\Support\Authentication;
use Tests\TestCase;

class AddressControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setup();
        $this->testleague = League::factory()->selected(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
