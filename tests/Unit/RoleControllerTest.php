<?php

namespace Tests\Unit;

use App\Models\Region;
use App\Enums\Role;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class RoleControllerTest extends TestCase
{
    use Authentication;

    /**
     * index.
     *
     * @test
     * @group role
     * @group controller
     *
     * @return void
     */
    public function index()
    {
      $response = $this->authenticated()
                       ->post(route('role.index'));

      $roles = array();
      foreach(Role::getInstances() as $role){
          $roles[] = array(
                "id"=>$role->value,
                "text"=>$role->description
              );
      }

      $response->assertStatus(200)
               ->assertJson($roles);

       $response = $this->authenticated()
                        ->post(route('role.index',['scope'=>'LEAGUE']));
       $l[] = Role::coerce('LeagueLead');
       $roles = array();
       foreach($l as $role){
           $roles[] = array(
                 "id"=>$role->value,
                 "text"=>$role->description
               );
       }

       $response->assertStatus(200)
                ->assertJson($roles);
    }

}
