<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\Club;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;

class ClubMembershipControllerTest extends TestCase
{
    use Authentication;

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
            ->get(route('membership.club.create', ['language' => 'de', 'club' => static::$testclub]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_new')
            ->assertViewHas('entity', static::$testclub)
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
            ->post(route('member.store', ['club' => static::$testclub]), [
                'member_id' => null,
                'firstname' => 'testfirstname',
                'lastname' => 'testmember',
                'zipcode' => '1111',
                'city' => 'testcity',
                'street' => 'anystreet',
                'mobile' => '123456',
                'phone' => '123456',
                'email1' => '12345',
                'entity_id' => static::$testclub->id,
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
                'entity_id' => static::$testclub->id,
                'entity_type' => Club::class,
                'function' => null,
                'email' => null,
            ]);

        $response->assertRedirect(route('club.dashboard', ['language' => 'de', 'club' => static::$testclub]))
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
        $member = static::$testclub->members->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'backto' => url(route('club.dashboard', ['club' => static::$testclub, 'language' => 'de'])),
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
        $member = static::$testclub->members->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'email1' => 'test2@gmail.com',
                'backto' => url(route('club.dashboard', ['club' => static::$testclub, 'language' => 'de'])),
            ]);

        $response->assertRedirect(route('club.dashboard', ['language' => 'de', 'club' => static::$testclub]))
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
        $member = static::$testclub->members->first();

        $response = $this->authenticated()
            ->post(route('membership.club.add', ['club' => static::$testclub, 'member' => $member]), [
                'function' => 'function',
                'email' => 'email'
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
        $member = static::$testclub->members->first();

        $response = $this->authenticated()
            ->post(route('membership.club.add', ['club' => static::$testclub, 'member' => $member]), [
                'selRole' => Role::getRandomValue(),
                'function' => 'function',
                'email' => 'email@gmail.com'
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
        $member = static::$testclub->members->first();
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
        $member = static::$testclub->members->first();
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
        $member2 = static::$testclub->members->first();
        $response = $this->authenticated()
            ->delete(route('membership.club.destroy', ['club' => static::$testclub, 'member' => $member2]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('members', ['id' => $member2->id])
            ->assertDatabaseMissing('memberships', ['member_id' => $member2->id])
            ->assertDatabaseCount('memberships', 4);
    }
}
