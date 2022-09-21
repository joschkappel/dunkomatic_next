<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\Membership;
use App\Enums\Role;
use App\Models\Club;
use App\Models\Invitation;
use App\Models\League;
use App\Notifications\InviteUser;
use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Notification;

class MemberControllerTest extends TestCase
{
    use Authentication;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * index
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function index()
    {

        $response = $this->authenticated()
            ->get(route('member.index', ['language' => 'de', 'region' => $this->region]));

        $response->assertStatus(200)
            ->assertViewIs('member.member_list');
    }
    /**
     * datatable
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function datatable()
    {
        // test base level region
        $response = $this->authenticated()
            ->get(route('member.datatable', ['region' => $this->testleague->region]));

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $this->testleague->region->members->first()->id]);
    }

    /**
     * edit
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
        $member = $this->testleague->region->members->first();

        $response = $this->authenticated()
            ->get(route('member.edit', ['language' => 'de', 'member' => $member, 'member-region'=>$this->testleague->region->id]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertViewIs('member.member_edit')
            ->assertViewHas('member', $member);
    }

    /**
     * store NOT OK
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function store_notok()
    {
        $response = $this->authenticated()
            ->post(route('member.store'), [
                'firstname' => 'firstname',
                'lastname' => 'testmember',
                'zipcode' => 'zipcodetoolong',
                'city' => 'city',
                'street' => 'street',
                'mobile' => 'mobileno',
                'email1' => 'noemail',
            ]);
        $response->assertStatus(302)
            //->assertSessionHasNoErrors();
            ->assertSessionHasErrors(['zipcode', 'email1']);

        $this->assertDatabaseMissing('members', ['lastname' => 'testmember']);
    }
    /**
     * store OK
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function store_ok()
    {

        $response = $this->authenticated()
            ->post(route('member.store'), [
                'firstname' => 'firstname',
                'lastname' => 'testmember',
                'zipcode' => 'zipcode',
                'city' => 'city',
                'street' => 'street',
                'mobile' => 'mobileno',
                'email1' => 'testmember@gmail.com',
                'role_id' => Role::ClubLead(),
                'entity_type' => Club::class,
                'entity_id' => $this->testclub_assigned->id,
                'member_id' => null,
                'function' => '',
                'email' => '',
            ]);

        $member = Member::where('lastname', 'testmember')->first();

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['lastname' => 'testmember']);
    }
    /**
     * update NOT OK
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        $member = Member::first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => 'firstname',
                'lastname' => 'testmember2',
                'zipcode' => 'zipcodetoolong',
                'city' => 'city',
                'street' => 'street',
                'mobile' => 'mobileno',
                'email1' => 'noemail',
            ]);
        $response->assertStatus(302)
            //->assertSessionHasNoErrors();
            ->assertSessionHasErrors(['zipcode', 'email1']);

        $this->assertDatabaseMissing('members', ['lastname' => 'testmember2']);
        $this->assertDatabaseHas('members', ['lastname' => $member->lastname]);
    }
    /**
     * update OK
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        $member = Member::first();

        $response = $this->authenticated()
            ->put(route('member.update', ['member' => $member]), [
                'firstname' => 'firstname',
                'lastname' => 'testmember2',
                'zipcode' => 'zipcode',
                'city' => 'city',
                'street' => 'street',
                'mobile' => 'mobileno',
                'email1' => 'testmember2@gmail.com',
                'backto' => url(route('member.index', ['language' => 'de', 'region' => $this->region]))
            ]);
        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('members', ['lastname' => 'testmember2']);
        $this->assertDatabaseMissing('members', ['lastname' => $member->lastname]);
    }
    /**
     * show
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function show()
    {
        $member = Member::first();

        $response = $this->authenticated()
            ->get(route('member.show', ['language' => 'de', 'member' => $member]));

        $response->assertStatus(200)
            ->assertJson($member->toArray());
    }
    /**
     * sb_region
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function sb_region()
    {

        // run for base level region
        $mship = Membership::whereIn('membership_id', $this->region->clubs()->pluck('id'))
            ->where('membership_type', Club::class)
            ->with('member')
            ->first();
        $member = $mship->member ?? null;
        $response = $this->authenticated()
            ->get(route('member.sb.region', ['region' => $this->region]));

        $response->assertStatus(200);

        if ($member) {
            $response->assertJsonFragment(["id" => $member->id, "text" => $member->name]);
        } else {
            $response->assertJson([]);
        }

        // run for top level
        $mship = Membership::whereIn('membership_id', $this->region->parentRegion->clubs()->pluck('id'))
            ->where('membership_type', Club::class)
            ->with('member')
            ->first();
        $member = $mship->member ?? null;
        $response = $this->authenticated()
            ->get(route('member.sb.region', ['region' => $this->region->parentRegion]));

        $response->assertStatus(200);

        if ($member) {
            $response->assertJsonFragment(["id" => $member->id, "text" => $member->name]);
        } else {
            $response->assertJson([]);
        }
    }
    /**
     * invite
     *
     * @test
     * @group member
     * @group controller
     *
     * @return void
     */
    public function invite()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $member = $this->testleague->region->members->first();

        $response = $this->authenticated()
            ->get(route('member.invite', ['member' => $member]));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('invitations', ['email_invitee'=>$member->email1]);
        Notification::assertSentTo($member, InviteUser::class);

        // clear invitataions
        Invitation::truncate();

    }

    /**
     * destroy
     *
     * @test
     * @group member
     * @group destroy
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        //$this->withoutExceptionHandling();
        $member = $this->testclub_assigned->members->first();

        $response = $this->authenticated()
            ->delete(route('member.destroy', ['member' => $member]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertJson(['deleted' => 1]);
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
    }
}
