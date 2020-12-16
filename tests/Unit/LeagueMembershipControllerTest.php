<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Member;
use App\Models\Membership;
use App\Models\League;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class LeagueMembershipControllerTest extends TestCase
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
      $league = League::factory()->create(['name'=>'testleague']);
      $member = Member::factory()->create(['lastname'=>'testmember']);

      $response = $this->authenticated( )
                        ->get(route('membership.league.create',['language'=>'de', 'league'=>$league]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_league_new')
               ->assertViewHas('league',$league)
               ->assertViewHas('members');

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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.league.store', ['league'=>$league]), [
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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.league.store', ['league'=>$league]), [
                          'member_id' => $member->id,
                          'selRole' => Role::getRandomValue()
                      ]);
      $response->assertRedirect(route('league.dashboard', ['language'=>'de','league'=>$league]))
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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->get(route('membership.league.edit',['language'=>'de',
                                           'league'=>$league, 'member'=>$member]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_league_edit')
               ->assertViewHas('league',$league)
               ->assertViewHas('members')
               ->assertViewHas('member',$member);
//               ->assertViewHas('memberships');
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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::factory()->create(['lastname'=>'testmember2']);

      $response = $this->authenticated( )
                        ->put(route('membership.league.update', ['league'=>$league,'member'=>$member]), [
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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->put(route('membership.league.update', ['league'=>$league,'member'=>$member]), [
                          'member_id' => $member2->id,
                          'selRole' => Role::getRandomValue(),
                      ]);

      $response->assertRedirect(route('league.dashboard', ['language'=>'de','league'=>$league]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('memberships', ['member_id' => $member2->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 4);
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
      $league = League::where('name','testleague')->first();
      $member = $league->members()->first();
      $response = $this->authenticated( )
                        ->get(route('membership.league.index',['language'=>'de', 'league'=>$league]));

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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->delete(route('membership.league.destroy', ['league'=>$league,'member'=>$member2]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $response = $this->authenticated( )
                         ->delete(route('membership.league.destroy', ['league'=>$league,'member'=>$member]));

      $response->assertStatus(302)
                ->assertSessionHasNoErrors();

      $this->assertDatabaseMissing('memberships', ['member_id' => $member2->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 3);
    }
}
