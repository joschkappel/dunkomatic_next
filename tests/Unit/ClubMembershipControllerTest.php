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
      $club = Club::factory()->create(['name'=>'testclub']);

      $response = $this->authenticated( )
                        ->get(route('membership.club.create',['language'=>'de', 'club'=>$club]));

      $response->assertStatus(200)
               ->assertViewIs('member.member_new')
               ->assertViewHas('entity',$club)
               ->assertViewHas('entity_type',Club::class);

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

      $response = $this->authenticated( )
                        ->post(route('member.store', ['club'=>$club]), [
                          'member_id' => null,
                          'firstname' => 'testfirstname',
                          'lastname' => 'testmember',
                          'zipcode' => '1111',
                          'city' => 'testcity',
                          'street' => 'anystreet',
                          'mobile' => '123456',
                          'phone' => '123456',
                          'email1' => '12345',
                          'entity_id' => $club->id,
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
      $club = Club::where('name','testclub')->first();

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
                          'entity_id' => $club->id,
                          'entity_type' => Club::class,
                          'function' => null,
                          'email' => null,
                      ]);

      $response->assertRedirect(route('club.dashboard', ['language'=>'de','club'=>$club]))
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated( )
                        ->put(route('member.update', ['member'=>$member]), [
                          'firstname' => $member->firstname,
                          'lastname' => 'testmember2',
                          'zipcode' => $member->zipcode,
                          'city' => $member->city,
                          'street' => $member->street,
                          'mobile' => $member->mobile,
                          'backto' => url(route('club.dashboard', ['club'=>$club, 'language'=>'de'])),
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
      $club = Club::where('name','testclub')->first();
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
                          'backto' => url(route('club.dashboard', ['club'=>$club, 'language'=>'de'])),                      ]);

      $response->assertRedirect(route('club.dashboard', ['language'=>'de','club'=>$club]))
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.club.add', ['club'=>$club, 'member'=>$member]), [
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
      $club = Club::where('name','testclub')->first();
      $member = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->post(route('membership.club.add', ['club'=>$club, 'member'=>$member]), [
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
      $club = Club::where('name','testclub')->first();
      $member2 = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated( )
                        ->delete(route('membership.club.destroy', ['club'=>$club,'member'=>$member2]));

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
        $club = Club::where('name','testclub')->delete();
        $member = Member::where('lastname','testmember')->delete();
        $member2 = Member::where('lastname','testmember2')->delete();

        $this->assertDatabaseCount('clubs', 0);
        $this->assertDatabaseCount('members', 5);
   }
}
