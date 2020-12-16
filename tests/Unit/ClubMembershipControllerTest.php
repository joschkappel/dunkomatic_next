<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Club;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

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
      $club = Club::factory()->create(['name'=>'testclub']);
      $member = Member::factory()->create(['lastname'=>'testmember']);

      $response = $this->authenticated( )
                        ->get(route('membership.club.create',['language'=>'de', 'club'=>$club]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_club_new')
               ->assertViewHas('club',$club)
               ->assertViewHas('members',$club->members()->get());

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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.club.store', ['club'=>$club]), [
                          'member_id' => $member->id,
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['selRole']);

      $this->assertDatabaseMissing('memberships', ['member_id' => $member->id]);
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.club.store', ['club'=>$club]), [
                          'member_id' => $member->id,
                          'selRole' => [Role::getRandomValue()]
                      ]);
      $response->assertRedirect(route('club.dashboard', ['language'=>'de','club'=>$club]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 4);
    }
    /**
     * edit
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function edit()
    {
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->get(route('membership.club.edit',['language'=>'de',
                                           'club'=>$club, 'member'=>$member]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_club_edit')
               ->assertViewHas('club',$club)
               ->assertViewHas('members',$club->members()->get())
               ->assertViewHas('member',$member);
//      $response->assertViewHas('memberships',$club->memberships()->where('member_id', $member->id)->get());
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::factory()->create(['lastname'=>'testmember2']);

      $response = $this->authenticated( )
                        ->put(route('membership.club.update', ['club'=>$club,'member'=>$member]), [
                          'member_id' => $member2->id,
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['selRole']);

      $this->assertDatabaseHas('memberships', ['member_id' => $member->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member2->id]);
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->put(route('membership.club.update', ['club'=>$club,'member'=>$member]), [
                          'member_id' => $member2->id,
                          'selRole' => [Role::getRandomValue(),Role::getRandomValue()]
                      ]);

      $response->assertRedirect(route('club.dashboard', ['language'=>'de','club'=>$club]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('memberships', ['member_id' => $member2->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 5);
    }
    /**
     * index
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
    public function index()
    {
      $club = Club::where('name','testclub')->first();
      $member = $club->members()->first();
      $response = $this->authenticated( )
                        ->get(route('membership.club.index',['language'=>'de', 'club'=>$club]));

      //$response->dump();
      $response->assertStatus(200)
               ->assertJson([['id'=>$member->id,'text'=>$member->name]]);
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->delete(route('membership.club.destroy', ['club'=>$club,'member'=>$member2]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();
      $response = $this->authenticated( )
                       ->delete(route('membership.club.destroy', ['club'=>$club,'member'=>$member]));

      $response->assertStatus(302)
              ->assertSessionHasNoErrors();

      $this->assertDatabaseMissing('memberships', ['member_id' => $member2->id]);
      $this->assertDatabaseMissing('memberships', ['member_id' => $member->id]);
      $this->assertDatabaseCount('memberships', 3);
    }
}
