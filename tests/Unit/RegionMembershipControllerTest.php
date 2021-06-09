<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Member;
use App\Enums\Role;

use App\Notifications\InviteUser;

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
      $region = Region::factory()->create(['name' => 'testregion', 'code' => 'HBVT','hq' => 'HBV','created_at' => now()]);
      $member = Member::factory()->create(['lastname'=>'testmember']);

      $response = $this->authenticated( )
                        ->get(route('membership.region.create',['language'=>'de', 'region'=>$region]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_region_new')
               ->assertViewHas('region',$region)
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
      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->post(route('membership.region.store', ['region'=>$region]), [
                          'member_id' => $member->id,
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['selRole']);

      $this->assertDatabaseMissing('memberships', ['member_id' => $member->id]);

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
      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->post(route('membership.region.store', ['region'=>$region]), [
                          'member_id' => $member->id,
                          'selRole' => Role::RegionLead()
                      ]);
      $response->assertRedirect(route('region.index', ['language'=>'de','region'=>$region]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 4);

      Notification::assertSentTo(
        [$member], InviteUser::class
      );

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
      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->get(route('membership.region.edit',['language'=>'de',
                                           'region'=>$region, 'member'=>$member]));

      $response->assertStatus(200)
               ->assertViewIs('member.membership_region_edit')
               ->assertViewHas('region',$region)
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
      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::factory()->create(['lastname'=>'testmember2']);
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->put(route('membership.region.update', ['region'=>$region,'member'=>$member]), [
                          'member_id' => $member2->id,
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['selRole']);

      $this->assertDatabaseHas('memberships', ['member_id' => $member->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member2->id]);

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

      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->put(route('membership.region.update', ['region'=>$region,'member'=>$member]), [
                          'member_id' => $member2->id,
                          'selRole' => Role::RegionTeam(),
                      ]);

      $response->assertRedirect(route('region.index', ['language'=>'de','region'=>$region]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('memberships', ['member_id' => $member2->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 4);

           Notification::assertSentTo(
        [$member2], InviteUser::class
        );
    }

    public function destroy()
    {
      $region = Region::where('name','testregion')->first();
      $member = Member::where('lastname','testmember')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->delete(route('membership.region.destroy', ['region'=>$region,'member'=>$member2]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $response = $this->authenticated( )
                         ->delete(route('membership.region.destroy', ['region'=>$region,'member'=>$member]));

      $response->assertStatus(302)
                ->assertSessionHasNoErrors();

      $this->assertDatabaseMissing('memberships', ['member_id' => $member2->id])
           ->assertDatabaseMissing('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 3);
    }
    /**
     * db_cleanup
     *
     * @test
     * @group membership
     * @group controller
     *
     * @return void
     */
   public function db_cleanup()
   {
        /// clean up DB
        $region = Region::where('name','testregion')->delete();
        $member = Member::where('lastname','testmember')->delete();
        $member2 = Member::where('lastname','testmember2')->delete();

        $this->assertDatabaseCount('regions', 5);
   }
}
