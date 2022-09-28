<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Region;
use Tests\Support\Authentication;
use Tests\TestCase;

class RegionMembershipControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()
            ->hasAttached(Member::factory()->count(1), ['role_id' => Role::LeagueLead()])
            ->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * create
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function create()
    {
        $response = $this->authenticated()
            ->get(route('membership.region.create', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_new')
            ->assertViewHas('entity', $this->region)
            ->assertViewHas('entity_type', Region::class);
    }

    /**
     * add NOT OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function add_notok()
    {
        $member = $this->testclub_assigned->members->first();

        $response = $this->authenticated()
            ->post(route('membership.region.add', ['region' => $this->region, 'member' => $member]), [
                'function' => 'function',
                'email' => 'email',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'selRole']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
    }

    /**
     * add  OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function add_ok()
    {
        $member = $this->testclub_assigned->members->first();

        $response = $this->authenticated()
            ->post(route('membership.region.add', ['region' => $this->region, 'member' => $member]), [
                'selRole' => Role::getRandomValue(),
                'function' => 'function',
                'email' => 'email@gmail.com',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 7);

        $mships = $this->region->memberships()->where('member_id', $member->id)->first()->delete();
    }

    /**
     * destroy
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        $member = $this->testclub_assigned->members->first();
        $response = $this->authenticated()
            ->delete(route('membership.region.destroy', ['region' => $this->region, 'member' => $member]));

        $response->assertSessionHasNoErrors()
                ->assertStatus(302);

        $this->assertDatabaseMissing('memberships', ['member_id' => $member->id, 'membership_type' => Region::class])
            ->assertDatabaseCount('memberships', 6);
    }
}
