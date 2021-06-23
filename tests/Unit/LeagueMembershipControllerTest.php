<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\League;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;

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

      $response = $this->authenticated( )
                        ->get(route('membership.league.create',['language'=>'de', 'league'=>$league]));

      $response->assertStatus(200)
               ->assertViewIs('member.member_new')
               ->assertViewHas('entity',$league)
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
      $league = League::where('name','testleague')->first();

      $response = $this->authenticated( )
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
                          'entity_id' => $league->id,
                          'entity_type' => League::class,
                          'function' => null,
                          'email' => null,                               
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['role_id','email1']);

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
      $league = League::where('name','testleague')->first();

      $response = $this->authenticated( )
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
                          'entity_id' => $league->id,
                          'entity_type' => League::class,
                          'function' => null,
                          'email' => null,
                      ]);
      $response->assertRedirect(route('league.dashboard', ['language'=>'de','league'=>$league]))
               ->assertSessionHasNoErrors();

      $member = Member::where('lastname','testmember')->first();

      $this->assertDatabaseHas('members', ['id' => $member->id])
          ->assertDatabaseHas('memberships', ['member_id' => $member->id])
          ->assertDatabaseCount('memberships', 4);
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

      $response = $this->authenticated( )
                        ->put(route('member.update', ['member'=>$member]), [
                                    'firstname' => $member->firstname,
                                    'lastname' => 'testmember2',
                                    'zipcode' => $member->zipcode,
                                    'city' => $member->city,
                                    'street' => $member->street,
                                    'mobile' => $member->mobile,
                                    'backto' => url(route('league.dashboard', ['league'=>$league, 'language'=>'de'])),
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['email1']);

      $this->assertDatabaseHas('members', ['id' => $member->id])
        ->assertDatabaseHas('memberships', ['member_id' => $member->id])
        ->assertDatabaseCount('memberships', 4);
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

      $response = $this->authenticated( )
                        ->put(route('member.update', ['member'=>$member]), [
                                  'firstname' => $member->firstname,
                                  'lastname' => 'testmember2',
                                  'zipcode' => $member->zipcode,
                                  'city' => $member->city,
                                  'street' => $member->street,
                                  'mobile' => $member->mobile,
                                  'email1' => 'test2@gmail.com',
                                  'backto' => url(route('league.dashboard', ['league'=>$league, 'language'=>'de'])),    
                      ]);

      $response->assertRedirect(route('league.dashboard', ['language'=>'de','league'=>$league]))
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('members', ['lastname' => 'testmember2'])
          ->assertDatabaseHas('memberships', ['member_id' => $member->id])
          ->assertDatabaseCount('memberships', 4);
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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.league.add', ['league'=>$league, 'member'=>$member]), [
                          'function' => 'function',
                          'email' => 'email'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasErrors(['email', 'selRole']);

      $this->assertDatabaseHas('members', ['id' => $member->id])
        ->assertDatabaseHas('memberships', ['member_id' => $member->id])
        ->assertDatabaseCount('memberships', 4);

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
      $league = League::where('name','testleague')->first();
      $member = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.league.add', ['league'=>$league, 'member'=>$member]), [
                          'selRole' => Role::getRandomValue(),
                          'function' => 'function',
                          'email' => 'email@gmail.com'
                      ]);
      $response
          ->assertStatus(302)
          ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('members', ['id' => $member->id])
        ->assertDatabaseHas('memberships', ['member_id' => $member->id])
        ->assertDatabaseCount('memberships', 5);
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
      $member = Member::where('lastname','testmember2')->first();
      $membership = $member->memberships->first();

      $response = $this->authenticated( )
                        ->put(route('membership.update', ['membership'=>$membership]), [
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
      $member = Member::where('lastname','testmember2')->first();
      $membership = $member->memberships->first();

      $response = $this->authenticated( )
                        ->put(route('membership.update', ['membership'=>$membership]), [
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
      $league = League::where('name','testleague')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->delete(route('membership.league.destroy', ['league'=>$league,'member'=>$member2]));

      $response->assertStatus(302)
               ->assertSessionHasNoErrors();
      
      $this->assertDatabaseMissing('members', ['id' => $member2->id])
          ->assertDatabaseMissing('memberships', ['member_id' => $member2->id])
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
        $league = League::where('name','testleague')->delete();
        $member = Member::where('lastname','testmember')->delete();
        $member2 = Member::where('lastname','testmember2')->delete();

        $this->assertDatabaseCount('leagues', 0);
        $this->assertDatabaseCount('members', 5);
   }
}
