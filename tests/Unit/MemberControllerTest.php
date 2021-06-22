<?php

namespace Tests\Unit;

use App\Models\Member;
use App\Models\Membership;
use App\Enums\Role;
use App\Models\Club;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Log;

class MemberControllerTest extends TestCase
{
    use Authentication;

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
               ->assertSessionHasErrors(['zipcode','email1']);

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
      Club::factory()->create(['name'=>'testclub']);
      $club = Club::where('name','testclub')->first();

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
                          'entity_id' => $club->id,
                          'member_id' => null,
                          'function' => '',
                          'email' => '',
                      ]);

      $member = Member::where('lastname','testmember')->first();

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
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated()
                        ->put(route('member.update', ['member'=>$member]), [
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
               ->assertSessionHasErrors(['zipcode','email1']);

      $this->assertDatabaseMissing('members', ['lastname' => 'testmember2']);
      $this->assertDatabaseHas('members', ['lastname' => 'testmember']);

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
      $member = Member::where('lastname','testmember')->first();

      $response = $this->authenticated()
                        ->put(route('member.update', ['member'=>$member]), [
                          'firstname' => 'firstname',
                          'lastname' => 'testmember2',
                          'zipcode' => 'zipcode',
                          'city' => 'city',
                          'street' => 'street',
                          'mobile' => 'mobileno',
                          'email1' => 'testmember2@gmail.com',
                          'backto' => url(route('member.index', ['language'=>'de']))
                      ]);
      $response->assertStatus(302)
               ->assertSessionHasNoErrors();

      $this->assertDatabaseHas('members', ['lastname' => 'testmember2']);
      $this->assertDatabaseMissing('members', ['lastname' => 'testmember']);

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
      $member = Member::where('lastname','testmember2')->first();

      $response = $this->authenticated()
                        ->get(route('member.show',['language'=>'de','member'=>$member]));

      $response->assertStatus(200)
               ->assertJson( $member->toArray());

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

      $mship = Membership::whereIn('membership_id', $this->region->clubs()->pluck('id'))
                           ->where('membership_type',Club::class)
                           ->with('member')
                           ->first();
      $member = $mship->member;
      $response = $this->authenticated()
                        ->get(route('member.sb.region',['region'=>$this->region]));

      //$response->dump();
      $response->assertStatus(200);

      if ($member){
        $response->assertJsonFragment(["id"=>$member->id, "text"=>$member->name]);
      } else {
        $response->assertJson([]);
      }
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
       $member = Member::where('lastname','testmember2')->first();
       $response = $this->authenticated( )
                         ->delete(route('member.destroy',['member'=>$member]));

       $response->assertStatus(200)
                ->assertSessionHasNoErrors();
       $this->assertDatabaseMissing('members', ['id'=>$member->id]);
     }
     /**
      * cleanup
      *
      * @test
      * @group member
      * @group db_cleanup
      * @group controller
      *
      * @return void
      */
      public function db_cleanup()
      {
        Club::where('name','testclub')->delete();
        
      }     
}
