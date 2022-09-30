<?php

namespace Tests\Feature\Controllers;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use Tests\Support\Authentication;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use Authentication;

    /**
     * index_all
     *
     * @test
     * @group role
     * @group controller
     *
     * @return void
     */
    public function index_all()
    {
        $response = $this->authenticated()
            ->post(route('role.index'));

        $roles = [];
        foreach (Role::getInstances() as $role) {
            $roles[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        $response->assertStatus(200)
            ->assertJson($roles);

        $response = $this->authenticated()
            ->post(route('role.index', ['scope' => League::class]));
        $l[] = Role::coerce('LeagueLead');
        $roles = [];
        foreach ($l as $role) {
            $roles[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        $response->assertStatus(200)
            ->assertJson($roles);
    }

    /**
     * index_league
     *
     * @test
     * @group role
     * @group controller
     *
     * @return void
     */
    public function index_league()
    {
        $response = $this->authenticated()
            ->post(route('role.index', ['scope' => League::class]));
        $l[] = Role::coerce('LeagueLead');
        $roles = [];
        foreach ($l as $role) {
            $roles[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        $response->assertStatus(200)
            ->assertJson($roles);
    }

    /**
     * index_club
     *
     * @test
     * @group role
     * @group controller
     *
     * @return void
     */
    public function index_club()
    {
        $response = $this->authenticated()
            ->post(route('role.index', ['scope' => Club::class]));

        $l[] = Role::coerce('ClubLead');
        $l[] = Role::coerce('RefereeLead');
        $l[] = Role::coerce('RegionTeam');
        $l[] = Role::coerce('JuniorsLead');
        $l[] = Role::coerce('GirlsLead');
        $roles = [];
        foreach ($l as $role) {
            $roles[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        $response->assertStatus(200)
            ->assertJson($roles);
    }

    /**
     * index_region
     *
     * @test
     * @group role
     * @group controller
     *
     * @return void
     */
    public function index_region()
    {
        $response = $this->authenticated()
            ->post(route('role.index', ['scope' => Region::class]));
        $l[] = Role::coerce('RegionLead');
        $l[] = Role::coerce('RegionTeam');
        $roles = [];
        foreach ($l as $role) {
            $roles[] = [
                'id' => $role->value,
                'text' => $role->description,
            ];
        }

        $response->assertStatus(200)
            ->assertJson($roles);
    }
}
