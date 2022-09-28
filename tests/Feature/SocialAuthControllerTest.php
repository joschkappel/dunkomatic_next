<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\NewUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as TwoUser;
use Mockery;
use Tests\TestCase;

class SocialAuthControllerTest extends TestCase
{
    /**
     * redirect_to_oauth
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function redirect_to_oauth()
    {
        Invitation::truncate();
        // without invite
        $response = $this->authenticated()
            ->get(route('oauth.redirect', ['provider' => 'google']));

        // $response->dumpHeaders();
        $response->assertStatus(302)
            ->assertCookieMissing('_i');

        // now with invite
        $invite = Invitation::create([
            'email_invitee' => 'test@gmail.com',
            'member_id' => $this->region->members->first()->id,
            'user_id' => $this->region_user->id,
            'region_id' => $this->region->id,
        ]);
        $response = $this->authenticated()
            ->get(route('oauth.redirect', ['provider' => 'google', 'invitation' => $invite]));

        // $response->dumpHeaders();
        $response->assertStatus(302)
            ->assertCookie('_i');
        $invite->refresh();
        $this->assertDatabaseHas('invitations', ['provider' => 'google']);
        Invitation::truncate();
    }

    /**
     * showApplyForm
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function showApplyForm()
    {
        $response = $this->authenticated()
            ->get(route('show.apply', ['language' => 'de', 'user' => $this->region_user]));

        $response->assertStatus(200)
            ->assertSessionHasNoErrors()
            ->assertViewIs('auth.apply')
            ->assertViewHas('user', $this->region_user);
    }

    /**
     * apply not ok
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function apply_notok()
    {
        Notification::fake();
        Notification::assertNothingSent();
        Event::fake();

        $response = $this->post(route('show.apply', ['language' => 'de', 'user' => $this->region_user]));

        $response->assertStatus(302)
            ->assertSessionHasErrors('region_id', 'reason_join');
        Notification::assertNothingSent();
        Event::assertNotDispatched(Registered::class);
    }

    /**
     * apply ok
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function apply_ok()
    {
        Notification::fake();
        Notification::assertNothingSent();
        Event::fake();

        $user = User::where('name', 'notapproved')->first();
        $response = $this->post(route('show.apply', ['language' => 'de', 'user' => $user]), [
            'region_id' => $this->region->id,
            'reason_join' => 'testing',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();

        Notification::assertSentTo(
            $this->region_user,
            NewUser::class
        );
        Event::assertDispatched(Registered::class);
    }

    /**
     * registerFromOauth
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function registerFromOauth()
    {
        // mock the socialite driver
        $abstractUser = Mockery::mock(TwoUser::class);
        $abstractUser->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn(Str::random(10))
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10).'@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://i.pravatar.cc/100');
        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);
        //

        $response = $this->get(route('oauth.callback', ['provider' => 'google']), [
            'region_id' => $this->region->id,
            'reason_join' => 'testing',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
    }

    /**
     * registerFromOauth with invite
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function registerFromOauth_invite()
    {
        Invitation::truncate();
        // mock the socialite driver
        $abstractUser = Mockery::mock(TwoUser::class);
        $abstractUser->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn(Str::random(10))
            ->shouldReceive('getEmail')
            ->andReturn(Str::random(10).'@gmail.com')
            ->shouldReceive('getAvatar')
            ->andReturn('https://i.pravatar.cc/100');
        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);
        //

        $invite = Invitation::create([
            'email_invitee' => 'test@gmail.com',
            'member_id' => $this->region->members->first()->id,
            'user_id' => $this->region_user->id,
            'region_id' => $this->region->id,
        ]);
        $this->assertDatabaseHas('invitations', ['id' => $invite->id]);

        $response = $this->withCookies(['_i' => $invite->id])
            ->get(route('oauth.callback', ['provider' => 'google']), [
                'region_id' => $this->region->id,
                'reason_join' => 'testing',
            ]);

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('invitations', ['id' => $invite->id]);
        Invitation::truncate();
    }

    /**
     * loginFromOauth
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function loginFromOauth()
    {
        // mock the socialite driver
        $abstractUser = Mockery::mock(TwoUser::class);
        $abstractUser->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn(Str::random(10))
            ->shouldReceive('getEmail')
            ->andReturn($this->region_user->email)
            ->shouldReceive('getAvatar')
            ->andReturn('https://i.pravatar.cc/100');
        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);
        //

        $response = $this->get(route('oauth.callback', ['provider' => 'google']));

        $response->assertStatus(302)
            ->assertSessionHasNoErrors();
    }
}
