<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\Region;
use Rappasoft\LaravelAuthenticationLog\Notifications\FailedLogin;

use TestDatabaseSeeder;

class LoginTest extends TestCase
{

     // use RefreshDatabase;

     /**
      * @test
      * @group auth
      */

     public function test_user_can_view_a_login_form()
     {
         $response = $this->get('/de/login');

         $response->assertSuccessful();
         $response->assertViewIs('auth.login');
     }

     /**
      * @test
      * @group auth
      */
     public function test_user_cannot_view_a_login_form_when_authenticated()
     {

         $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
         $region = Region::where('code','HBVDA')->first();

         $region_user = $region->regionadmins->first()->user()->first();

         $response = $this->actingAs($region_user)->get('/de/login');

         $response->assertRedirect('/de/home');
     }

     /**
      * @test
      * @group auth
      */
     public function test_user_can_login_with_correct_credentials()
     {
       $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
       $region = Region::where('code','HBVDA')->first();

       $region_user = $region->regionadmins->first()->user()->first();

         $response = $this->post('/de/login', [
             'email' => $region_user->email,
             'password' => 'password',
         ]);

         $response->assertRedirect('/de/home');
         $this->assertAuthenticatedAs($region_user);
     }

     /**
      * @test
      * @group auth
      */
     public function test_user_cannot_login_with_incorrect_password()
     {
       $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
       $region = Region::where('code','HBVDA')->first();

       Notification::fake();
       Notification::assertNothingSent();

       $region_user = $region->regionadmins->first()->user()->first();

         $response = $this->from('/de/login')->post('/de/login', [
             'email' => $region_user->email,
             'password' => 'invalid-password',
         ]);

         $response->assertRedirect('/de/login');
         $response->assertSessionHasErrors('email');
         $this->assertTrue(session()->hasOldInput('email'));
         $this->assertFalse(session()->hasOldInput('password'));
         $this->assertGuest();

         Notification::assertSentTo(
            [$region_user], FailedLogin::class
        );

     }

}
