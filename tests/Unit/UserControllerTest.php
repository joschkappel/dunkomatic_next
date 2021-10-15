<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Models\Region;
use App\Models\Club;
use App\Models\League;

use App\Notifications\VerifyEmail;

class UserControllerTest extends TestCase
{

    /**
     * create
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function create()
    {

      $response = $this->get(route('register',['language'=>'de']));

      // $response->dump();
      $response->assertStatus(200)
               ->assertViewIs('auth.register');
    }


    /**
     * show
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function show()
    {
        $user =  User::where("email","=","approved@gmail.com")->first();

        $response = $this->authenticated()
                         ->followingRedirects()
                         ->get(route('admin.user.show',[
                            'language'=>'de',
                            'user' => $user
        ]));

        $response->assertStatus(200)
                 ->assertViewIs('auth.user_profile');

    }

    /**
     * update not ok
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function update_notok()
    {
        $user =  User::where("email","=","approved@gmail.com")->first();

        $response = $this->authenticated()
                         ->put(route('admin.user.update',[
                            'user' => $user,
                            'name' => '',
                            'email' => $user->email
        ]));

        $response->assertStatus(302)
                 ->assertSessionHasErrors(['name','locale']);;

    }

    /**
     * update ok
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function update_ok()
    {
        $user =  User::where("email","=","approved@gmail.com")->first();

        $response = $this->authenticated()
                         ->put(route('admin.user.update',[
                            'user' => $user,
                            'locale' => 'en',
                            'name' => 'testuser2',
                            'email' => $user->email
        ]));

        $user->refresh();

        $response->assertStatus(302)
                 ->assertSessionHasNoErrors()
                 ->assertHeader('Location', route('home',['language'=>'en']));

        $this->assertDatabaseHas('users', ['name'=>'testuser2']);

    }

    /**
     * update email ok
     *
     * @test
     * @group user
     * @group controller
     *
     * @return void
     */
    public function update_email_ok()
    {
        Notification::fake();
        Notification::assertNothingSent();

        $user =  User::where("email","=","approved@gmail.com")->first();

        $response = $this->authenticated()
                         ->followingRedirects()
                         ->put(route('admin.user.update',[
                            'user' => $user,
                            'locale' => 'de',
                            'name' => 'testuser2',
                            'email' => 'anewemail@gmail.com'
        ]));

         $response->assertStatus(200)
                 ->assertSessionHasNoErrors()
                 ->assertViewIs('auth.login');

        $this->assertDatabaseHas('users', ['name'=>'testuser2']);
        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );

    }

}
