<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Region;
use App\Notifications\NewUser;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestUsers;


class RegistrationTest extends TestCase
{
     protected $testUser;

     public function __construct() {
         $this->testUser = new TestUsers();
         parent::__construct();
     }


     use RefreshDatabase;

     /**
      * @test
      * @group auth
      */
     public function user_registers()
     {
         $region_admin = $this->testUser->getRegionUser();
         $region = Region::factory()->create();

         Notification::fake();
         Notification::assertNothingSent();

         $response = $this->get('/de/register');

         $response->assertSuccessful();
         $response->assertViewIs('auth.register');
         $response = $this->post('/de/register',[
                           'name'=> 'tester',
                           'email'=> 'test@gmail.com',
                           'password'=> 'password',
                           'password_confirmation'=> 'password',
                           'reason_join'=> 'am testing',
                           'region' => $region->id
                         ]);
         $response->assertStatus(302)
                  ->assertHeader('Location', url('/home'));
         $response = $this->get('home');
         $response->assertStatus(302)
                  ->assertHeader('Location', '/de/home');
         $response = $this->get('de/home');
         $response->assertStatus(302)
                  ->assertHeader('Location', url('/de/email/verify'));
         $response = $this->get('de/email/verify');
         $response->assertStatus(200);
         $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
         $user =  User::where("email","=","test@gmail.com")->first();

         Notification::assertSentTo(
           [$user], VerifyEmail::class
         );

         Notification::assertSentTo(
           [$region_admin], NewUser::class
         );

         $user->update(['approved_at'=>now(),'email_verified_at'=>now() ]);

         // check login
         $response = $this->post('/de/login', [
             'email' => $user->email,
             'password' => $user->password,
         ]);

         $response->assertRedirect('/de/home');
         $this->assertAuthenticatedAs($user);


     }

}
