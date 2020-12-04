<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Region;

use TestDatabaseSeeder;

class LoginTest extends TestCase
{

     use RefreshDatabase;

    /** @test **/
     public function test_user_can_view_a_login_form()
     {
         $response = $this->get('/de/login');

         $response->assertSuccessful();
         $response->assertViewIs('auth.login');
     }

    /** @test **/
     public function test_user_cannot_view_a_login_form_when_authenticated()
     {

         $this->seed(TestDatabaseSeeder::class);
         $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

         $region = Region::where('code','HBVDA')->first();
         $region_user = $region->regionadmin->first()->user()->first();

         $response = $this->actingAs($region_user)->get('/de/login');

         $response->assertRedirect('/de/home');
     }

    /** @test **/
     public function test_user_can_login_with_correct_credentials()
     {
       $this->seed(TestDatabaseSeeder::class);
       $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

       $region = Region::where('code','HBVDA')->first();
       $region_user = $region->regionadmin->first()->user()->first();

         $response = $this->post('/de/login', [
             'email' => $region_user->email,
             'password' => 'password',
         ]);

         $response->assertRedirect('/home');
         $this->assertAuthenticatedAs($region_user);
     }

     public function test_user_cannot_login_with_incorrect_password()
     {
       $this->seed(TestDatabaseSeeder::class);
       $this->assertDatabaseHas('users', ['region' => 'HBVDA']);

       $region = Region::where('code','HBVDA')->first();
       $region_user = $region->regionadmin->first()->user()->first();

         $response = $this->from('/de/login')->post('/de/login', [
             'email' => $region_user->email,
             'password' => 'invalid-password',
         ]);

         $response->assertRedirect('/de/login');
         $response->assertSessionHasErrors('email');
         $this->assertTrue(session()->hasOldInput('email'));
         $this->assertFalse(session()->hasOldInput('password'));
         $this->assertGuest();
     }

}
