<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\User;
use App\Notifications\ApproveUser;
use App\Notifications\NewUser;
use App\Notifications\RejectUser;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->selected(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * register
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function register()
    {
        $this->assertDatabaseHas('regions', ['code' => 'HBVDA']);
        $region = Region::where('code', 'HBVDA')->first();

        $region_admin = $region->regionadmins()->first()->user()->first();

        Notification::fake();
        Notification::assertNothingSent();

        $response = $this->post(route('register', [
            'language' => 'de',
            'name' => 'tester1',
            'email' => 'test1@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'reason_join' => 'registration test 1',
            'region_id' => $region->id,
            'locale' => 'de',
            'captcha' => 'mockcaptcha=12345',
        ]));

        $response->assertStatus(302);

        //  assert user is created in DB
        $this->assertDatabaseHas('users', ['email' => 'test1@gmail.com']);
        $user = User::where('email', '=', 'test1@gmail.com')->first();

        //  assert region admin adn user are notified
        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );

        Notification::assertSentTo(
            [$region_admin], NewUser::class
        );

        //  assert user can NOT login
        $response = $this->followingRedirects()->post(route('login', [
            'language' => 'de',
            'email' => 'test1@gmail.com',
            'password' => 'password',
        ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.verify');
    }

    /**
     * index 2 new users
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function index_2_new_users()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();

        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->get(route('admin.user.index.new', [
                             'language' => 'de',
                             'region' => $region, ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.users')
                 ->assertSeeText('notapproved@gmail.com')
                 ->assertSeeText('test1@gmail.com');
    }

    /**
     * approve
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function approve()
    {
        $club = Club::all()->pluck('id')->toarray();

        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();
        $user = User::where('email', '=', 'test1@gmail.com')->first();

        Notification::fake();
        Notification::assertNothingSent();

        // approve user request
        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->post(route('admin.user.approve', [
                             'language' => 'de',
                             'approved' => 'on',
                             'user_id' => $user->id,
                             'club_ids' => $club,
                             'role' => 3,
                         ]));
        // $response->dump();
        $response->assertStatus(200)
                 ->assertViewIs('auth.users');

        //  assert region admin adn user are notified
        Notification::assertSentTo(
            [$user], ApproveUser::class
        );
    }

    /**
     * index 1 new users
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function index_1_new_user()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();

        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->get(route('admin.user.index.new', [
                             'language' => 'de',
                             'region' => $region, ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.users')
                 ->assertSeeText('notapproved@gmail.com')
                 ->assertDontSeeText('test1@gmail.com');
    }

    /**
     * allowance and index
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function allowance()
    {
        $user = User::where('email', '=', 'test1@gmail.com')->first();

        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();

        $clubs = $this->testleague->clubs->pluck('id')->toArray();

        $league = $this->testleague;

        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->put(route('admin.user.allowance', [
                             'user' => $user,
                             'club_ids' => $clubs,
                             'league_ids' => [$league->id],
                             'role' => 3,
                         ]));

        $response->assertStatus(200)
                 ->assertSessionHasNoErrors();
    }

    /**
     * reject
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function reject()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();
        $user = User::where('email', '=', 'notapproved@gmail.com')->first();

        Notification::fake();
        Notification::assertNothingSent();

        // approve user request
        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->post(route('admin.user.approve', [
                             'language' => 'de',
                             'reason_reject' => 'rejected test',
                             'user_id' => $user->id,
                             'role' => 3,
                         ]));
        $response->assertStatus(200)
                 ->assertViewIs('auth.users');

        //  assert region admin adn user are notified
        Notification::assertSentTo(
            [$user], RejectUser::class
        );
    }

    /**
     * index 0 new users
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function index_0_new_user()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();

        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->get(route('admin.user.index.new', [
                             'language' => 'de',
                             'region' => $region, ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.users')
                 ->assertDontSeeText('notapproved@gmail.com')
                 ->assertDontSeeText('test1@gmail.com');
    }

    /**
     * block
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function block()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();
        $user = User::where('email', '=', 'test1@gmail.com')->first();

        Notification::fake();
        Notification::assertNothingSent();

        // approve user request
        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->post(route('admin.user.block', [
                             'language' => 'de',
                             'user' => $user,
                         ]));
        $response->assertStatus(200)
                 ->assertViewIs('auth.user_list');
    }

    /**
     * index 1 new user
     *
     * @//test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function index_1_user()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();

        $response = $this->authenticated($region_admin)
                         ->followingRedirects()
                         ->get(route('admin.user.index.new', [
                             'language' => 'de', 'region' => $region, ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.users')
                 ->assertDontSeeText('notapproved@gmail.com')
                 ->assertSeeText('test1@gmail.com');
    }

    /**
     * destroy
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function destroy()
    {
        $region = Region::where('code', 'HBVDA')->first();
        $region_admin = $region->regionadmins()->first()->user()->first();
        $user = User::where('email', '=', 'test1@gmail.com')->first();

        $response = $this->authenticated($region_admin)
                         ->delete(route('admin.user.destroy', [
                             'language' => 'de',
                             'user' => $user,
                         ]));

        $response->assertStatus(302)
                 ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('users', ['email' => 'test1@gmail.com']);
    }
}
