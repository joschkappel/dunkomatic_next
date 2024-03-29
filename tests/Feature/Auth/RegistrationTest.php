<?php

namespace Tests\Feature\Auth;

use App\Models\Region;
use App\Models\User;
use App\Notifications\NewUser;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Mews\Captcha\Facades\Captcha;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * @test
     * @group auth
     */
    public function user_registers()
    {
        // $this->seed(TestDatabaseSeeder::class);
        $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
        $region = Region::where('code', 'HBVDA')->first();

        $region_admin = $region->regionadmins->first()->user()->first();

        Notification::fake();
        Notification::assertNothingSent();
        $this->assertDatabaseMissing('users', ['email' => 'test@gmail.com']);

        Captcha::shouldReceive('img')
        ->once()
        ->with('math', [])
        ->andReturn('12345');

        $response = $this->get('/de/register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
        $response = $this->followingRedirects()
                         ->post('/de/register', [
                             'name' => 'tester',
                             'email' => 'test@gmail.com',
                             'password' => 'password',
                             'password_confirmation' => 'password',
                             'reason_join' => 'am testing',
                             'region_id' => $region->id,
                             'locale' => 'de',
                             'captcha' => 'mockcaptcha=12345',
                         ])
                         ->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
        $user = User::where('email', 'test@gmail.com')->first();

        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );

        Notification::assertSentTo(
            [$region_admin], NewUser::class
        );

        $user->update(['approved_at' => now(), 'email_verified_at' => now()]);

        // check login
        $response = $this->post('/de/login', [
            'email' => $user->email,
            'password' => $user->password,
        ]);

        $response->assertRedirect('/de/home');
        $this->assertAuthenticatedAs($user);
    }
}
