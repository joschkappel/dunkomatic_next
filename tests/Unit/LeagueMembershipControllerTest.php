<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueMembershipControllerTest extends TestCase
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
            ->get(route('membership.league.create', ['language' => 'de', 'league' => $this->testleague]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_new')
            ->assertViewHas('entity', $this->testleague)
            ->assertViewHas('entity_type', League::class);
    }

    /**
     * store NOT OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
        $response = $this->authenticated()
            ->post(route('member.store'), [
                'member_id' => null,
                'firstname' => 'testfirstname',
                'lastname' => 'testmember',
                'zipcode' => '1111',
                'city' => 'testcity',
                'street' => 'anystreet',
                'mobile' => '123456',
                'phone' => '123456',
                'email1' => '12345',
                'entity_id' => $this->testleague->id,
                'entity_type' => League::class,
                'function' => null,
                'email' => null,
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['role_id', 'email1']);

        $this->assertDatabaseMissing('members', ['lastname' => 'testmember']);
    }

    /**
     * store OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {
        $response = $this->authenticated()
            ->post(route('member.store'), [
                'member_id' => null,
                'role_id' => Role::getRandomValue(),
                'firstname' => 'testfirstname',
                'lastname' => 'testmember',
                'zipcode' => '1111',
                'city' => 'testcity',
                'street' => 'anystreet',
                'mobile' => '123456',
                'phone' => '123456',
                'email1' => 'testlastname@gmail.com',
                'entity_id' => $this->testleague->id,
                'entity_type' => League::class,
                'function' => null,
                'email' => null,
            ]);
        $response->assertRedirect(route('league.dashboard', ['language' => 'de', 'league' => $this->testleague]))
            ->assertSessionHasNoErrors();

        $member = Member::where('lastname', 'testmember')->first();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 7);
        // roollback
        $member->delete();
    }

    /**
     * update NOT OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        $member = Member::has('memberships')->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'backto' => url(route('league.dashboard', ['league' => $this->testleague, 'language' => 'de'])),
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email1']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
    }

    /**
     * update OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function udpate_ok()
    {
        $member = $this->testclub_assigned->members()->first();
        $this->assertDatabaseHas('members', ['id' => $member->id]);

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'email1' => 'test2@gmail.com',
                'backto' => url(route('league.dashboard', ['league' => $this->testleague, 'language' => 'de'])),
            ]);

        $response->assertRedirect(route('league.dashboard', ['language' => 'de', 'league' => $this->testleague]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['lastname' => 'testmember2'])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
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
        $member = Member::doesnthave('memberships')->first();

        $response = $this->authenticated()
            ->post(route('membership.league.add', ['league' => $this->testleague, 'member' => $member]), [
                'function' => 'function',
                'email' => 'email',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'selRole']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
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
        $member = Member::doesnthave('memberships')->first();

        $response = $this->authenticated()
            ->post(route('membership.league.add', ['league' => $this->testleague, 'member' => $member]), [
                'selRole' => Role::LeagueLead,
                'function' => 'function',
                'email' => 'email@gmail.com',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 7);

        // rollback
        $member->memberships()->delete();
    }

    /**
     * update mship NOT OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function update_mship_notok()
    {
        $member = Member::has('memberships')->first();
        $membership = $member->memberships->first();

        $response = $this->authenticated()
            ->put(route('membership.update', ['membership' => $membership]), [
                'email' => '1234',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
    }

    /**
     * update mship OK
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function update_mship_ok()
    {
        $member = Member::has('memberships')->first();
        $membership = $member->memberships->first();

        $response = $this->authenticated()
            ->put(route('membership.update', ['membership' => $membership]), [
                'email' => 'mship@gmail.com',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
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
        $member = $this->testleague->members()->first();
        $this->assertDatabaseHas('members', ['id' => $member->id]);

        $response = $this->authenticated()
            ->delete(route('membership.league.destroy', ['league' => $this->testleague, 'member' => $member]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('members', ['id' => $member->id])
            ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 5);
    }
}
