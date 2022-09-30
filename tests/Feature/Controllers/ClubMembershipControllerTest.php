<?php

namespace Tests\Feature\Controllers;

use App\Enums\Role;
use App\Models\Club;
use App\Models\League;
use App\Models\Member;
use Tests\Support\Authentication;
use Tests\TestCase;

class ClubMembershipControllerTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 4)->create();
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
            ->get(route('membership.club.create', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_new')
            ->assertViewHas('entity', $this->testclub_assigned)
            ->assertViewHas('entity_type', Club::class);
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
            ->post(route('member.store', ['club' => $this->testclub_assigned]), [
                'member_id' => null,
                'firstname' => 'testfirstname',
                'lastname' => 'testmember',
                'zipcode' => '1111',
                'city' => 'testcity',
                'street' => 'anystreet',
                'mobile' => '123456',
                'phone' => '123456',
                'email1' => '12345',
                'entity_id' => $this->testclub_assigned->id,
                'entity_type' => Club::class,
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
                'entity_id' => $this->testclub_assigned->id,
                'entity_type' => Club::class,
                'function' => null,
                'email' => null,
            ]);

        $response->assertRedirect(route('club.dashboard', ['language' => 'de', 'club' => $this->testclub_assigned]))
            ->assertSessionHasNoErrors();

        $member = Member::where('lastname', 'testmember')->first();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
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
        $member = $this->testclub_assigned->members->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'backto' => url(route('club.dashboard', ['club' => $this->testclub_assigned, 'language' => 'de'])),
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email1']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 5);
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
        $member = $this->testclub_assigned->members->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'email1' => 'test2@gmail.com',
                'backto' => url(route('club.dashboard', ['club' => $this->testclub_assigned, 'language' => 'de'])),
            ]);

        $response->assertRedirect(route('club.dashboard', ['language' => 'de', 'club' => $this->testclub_assigned]))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['lastname' => 'testmember2'])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 5);
        $member->refresh();
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
            ->post(route('membership.club.add', ['club' => $this->testclub_assigned, 'member' => $member]), [
                'function' => 'function',
                'email' => 'email',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'selRole']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 5);
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
            ->post(route('membership.club.add', ['club' => $this->testclub_assigned, 'member' => $member]), [
                'selRole' => Role::getRandomValue(),
                'function' => 'function',
                'email' => 'email@gmail.com',
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 6);
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
        $member = $this->testclub_assigned->members->first();
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
            ->assertDatabaseCount('memberships', 5);
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
        $member = $this->testclub_assigned->members->first();
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
            ->assertDatabaseCount('memberships', 5);
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
        $member2 = $this->testclub_assigned->members->first();
        $response = $this->authenticated()
            ->delete(route('membership.club.destroy', ['club' => $this->testclub_assigned, 'member' => $member2]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('members', ['id' => $member2->id])
            ->assertDatabaseMissing('memberships', ['member_id' => $member2->id])
            ->assertDatabaseCount('memberships', 4);
    }

    /**
     * destroy mship
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function destroy_mship()
    {
        $membership = $this->testclub_assigned->memberships->first();
        $member = $membership->member;
        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['id' => $membership->id]);

        $response = $this->authenticated()
            ->delete(route('membership.destroy', ['membership' => $membership]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['success' => 'all good']);

        $this->assertDatabaseMissing('members', ['id' => $member->id])
            ->assertDatabaseMissing('memberships', ['id' => $membership->id])
            ->assertDatabaseCount('memberships', 4);
    }
}
