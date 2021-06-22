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

      $response = $this->authenticated( )
                        ->get(route('membership.region.create',['language'=>'de', 'region'=>$region]));

      $response->assertStatus(200)
               ->assertViewIs('member.member_new')
               ->assertViewHas('entity',$region)
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
      $region = Region::where('name','testregion')->first();
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->post(route('member.store'), [
                          'member_id' => null,
                          'firstname' => 'testfirstname',
                          'lastname' => 'testmember',
                          'zipcode' => '1111',
                          'city' => 'testcity',
                          'street' => 'anystreet',
                          'mobile' => '123456',
                          'phone1' => '123456',
                          'email1' => '12345',
                          'entity_id' => $region->id,
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
      $region = Region::where('name','testregion')->first();
      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->post(route('member.store'), [
                          'member_id' => null,
                          'role_id' => Role::RegionLead(),
                          'firstname' => 'testfirstname',
                          'lastname' => 'testmember',
                          'zipcode' => '1111',
                          'city' => 'testcity',
                          'street' => 'anystreet',
                          'mobile' => '123456',
                          'phone1' => '123456',
                          'email1' => 'testlastname@gmail.com',
                          'entity_id' => $region->id,
                          'entity_type' => Region::class,
                          'function' => null,
                          'email' => null,
                      ]);

      $response->assertRedirect(route('region.index', ['language'=>'de']))
               ->assertSessionHasNoErrors();
      $member = Member::where('lastname','testmember')->first();
      $this->assertDatabaseHas('members', ['id' => $member->id])
           ->assertDatabaseHas('memberships', ['member_id' => $member->id])
           ->assertDatabaseCount('memberships', 4);

      Notification::assertSentTo(
        [$member], InviteUser::class
      );

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

      Notification::fake();
      Notification::assertNothingSent();

      $response = $this->authenticated( )
                        ->put(route('member.update', ['member'=>$member]), [
                          'firstname' => $member->firstname,
                          'lastname' => 'testmember2',
                          'zipcode' => $member->zipcode,
                          'city' => $member->city,
                          'street' => $member->street,
                          'mobile' => $member->mobile,
                          'backto' => url(route('region.dashboard', ['region'=>$region, 'language'=>'de'])),
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['email1']);

      $this->assertDatabaseHas('members', ['id' => $member->id])
          ->assertDatabaseHas('memberships', ['member_id' => $member->id])
          ->assertDatabaseCount('memberships', 4);

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

      $response = $this->authenticated( )
                        ->put(route('member.update', ['member'=>$member]), [
                          'firstname' => $member->firstname,
                          'lastname' => 'testmember2',
                          'zipcode' => $member->zipcode,
                          'city' => $member->city,
                          'street' => $member->street,
                          'mobile' => $member->mobile,
                          'email1' => 'test2@gmail.com',
                          'backto' => url(route('region.dashboard', ['region'=>$region, 'language'=>'de'])),    
                      ]);

      $response->assertRedirect(route('region.dashboard', ['language'=>'de','region'=>$region]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('members', ['lastname' => 'testmember2'])
               ->assertDatabaseHas('memberships', ['member_id' => $member->id])
               ->assertDatabaseCount('memberships', 4);
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
