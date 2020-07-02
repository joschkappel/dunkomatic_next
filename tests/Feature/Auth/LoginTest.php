<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Tests\TestUsers;


class LoginTest extends TestCase
{
     protected $testUser;

     public function __construct() {
         $this->testUser = new TestUsers();
         parent::__construct();
     }


     use RefreshDatabase;

    /** @test **/
     public function test_user_can_view_a_login_form()
     {
         $response = $this->get('/login');

         $response->assertSuccessful();
         $response->assertViewIs('auth.login');
     }

    /** @test **/
     public function test_user_cannot_view_a_login_form_when_authenticated()
     {
         $region_user = $this->testUser->getRegionUser();

         $response = $this->actingAs($region_user)->get('/login');

         $response->assertRedirect('/home');
     }

    /** @test **/
     public function test_user_can_login_with_correct_credentials()
     {
         $region_user = $this->testUser->getRegionUser();

         $response = $this->post('/login', [
             'email' => $region_user->email,
             'password' => $this->testUser->getPassword(),
         ]);

         $response->assertRedirect('/home');
         $this->assertAuthenticatedAs($region_user);
     }

     public function test_user_cannot_login_with_incorrect_password()
     {
         $region_user = $this->testUser->getRegionUser();

         $response = $this->from('/login')->post('/login', [
             'email' => $region_user->email,
             'password' => 'invalid-password',
         ]);

         $response->assertRedirect('/login');
         $response->assertSessionHasErrors('email');
         $this->assertTrue(session()->hasOldInput('email'));
         $this->assertFalse(session()->hasOldInput('password'));
         $this->assertGuest();
     }

}
