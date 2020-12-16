<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Models\Member;
use App\Models\Membership;

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
               ->assertSessionHasErrorsIn('err_member',['zipcode','email1']);

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
                      ]);

      $member = Member::where('lastname','testmember')->first();

      $response->assertStatus(302)
               ->assertSessionHasNoErrors()
               ->assertSessionHas('member.lastname', $member->lastname);


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
               ->assertSessionHasErrorsIn('err_member',['zipcode','email1']);

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

      $member = $members = Membership::whereIn('membership_id', $this->region->clubs()->pluck('id'))
                           ->where('membership_type',Club::class)
                           ->with('member')
                           ->first();
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


}
