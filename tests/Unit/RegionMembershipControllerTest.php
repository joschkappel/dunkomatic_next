<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Member;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;

class RegionMembershipControllerTest extends TestCase
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
            ->get(route('membership.region.create', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_new')
            ->assertViewHas('entity', $this->region)
            ->assertViewHas('entity_type', Region::class);
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
        Notification::fake();
        Notification::assertNothingSent();

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
                'entity_id' => $this->region->id,
                'entity_type' => Region::class,
                'function' => null,
                'email' => null,
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['role_id', 'email1']);

        $this->assertDatabaseMissing('members', ['lastname' => 'testmember']);

        Notification::assertNothingSent();
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
        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->authenticated()
            ->post(route('member.store'), [
                'member_id' => null,
                'role_id' => Role::RegionLead(),
                'firstname' => 'testfirstname',
                'lastname' => 'testmember',
                'zipcode' => '1111',
                'city' => 'testcity',
                'street' => 'anystreet',
                'mobile' => '123456',
                'phone' => '123456',
                'email1' => 'testlastname@gmail.com',
                'entity_id' => $this->region->id,
                'entity_type' => Region::class,
                'function' => null,
                'email' => null,
            ]);

        $response->assertRedirect(route('region.index', ['language' => 'de']))
            ->assertSessionHasNoErrors();
        $member = Member::where('lastname', 'testmember')->first();
        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 2);

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
        $member = Member::where('lastname', 'testmember')->first();

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => $member->firstname,
                'lastname' => 'testmember2',
                'zipcode' => $member->zipcode,
                'city' => $member->city,
                'street' => $member->street,
                'mobile' => $member->mobile,
                'backto' => url(route('region.dashboard', ['region' => $this->region, 'language' => 'de'])),
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email1']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseCount('memberships', 2);

        Notification::assertNothingSent();
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

        $member = Member::where('lastname', 'testmember')->first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => 'otto',
                'lastname' => 'testmember2',
                'zipcode' => '1111',
                'city' => 'fra',
                'street' => 'street',
                'mobile' => '1234567',
                'email1' => 'test2@gmail.com',
                'backto' => url(route('region.dashboard', ['region' => $this->region, 'language' => 'de'])),
            ]);

        $response->assertSessionHasNoErrors()
                ->assertRedirect(route('region.dashboard', ['language' => 'de', 'region' => $this->region]));

        $this->assertDatabaseHas('members', ['lastname' => 'testmember2'])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 2);
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
        $member = Member::where('lastname', 'testmember2')->first();

        $response = $this->authenticated()
            ->post(route('membership.region.add', ['region' => $this->region, 'member' => $member]), [
                'function' => 'function',
                'email' => 'email'
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasErrors(['email', 'selRole']);

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 2);
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
        $member = Member::where('lastname', 'testmember2')->first();

        $response = $this->authenticated()
            ->post(route('membership.region.add', ['region' => $this->region, 'member' => $member]), [
                'selRole' => Role::getRandomValue(),
                'function' => 'function',
                'email' => 'email@gmail.com'
            ]);
        $response
            ->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['id' => $member->id])
            ->assertDatabaseHas('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 3);

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
        $member = Member::where('lastname', 'testmember2')->first();
        $response = $this->authenticated()
            ->delete(route('membership.region.destroy', ['region' => $this->region, 'member' => $member]));

        $response->assertSessionHasNoErrors()
                ->assertStatus(302);

                $this->assertDatabaseMissing('memberships', ['member_id' => $member->id])
            ->assertDatabaseCount('memberships', 1);
    }

}
