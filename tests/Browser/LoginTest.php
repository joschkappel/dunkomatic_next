<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user1;
    private $user2;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->user1 = User::factory()->create([
                  'email' => 'taylor@laravel.com',
                  'region' => 'HBV'
              ]);
        $this->user2 = User::factory()->create([
                  'email' => 'taylor3@laravel.com',
                  'region' => 'HBV',
                  'approved_at' => now(),
              ]);

    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testApproveWaiting()
    {

        $u1 = $this->user1;
        $this->browse(function ($first, $second) use ($u1) {
                  $first->visit('/de/login')
                          ->type('email', $u1->email)
                          ->type('password', 'password')
                          ->click('@login')
                          ->assertPathBeginsWith('/de')
                          ->assertPathIs('/de/approval');

              });
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testApproved()
    {
        $u2 = $this->user2;
        $this->browse(function ($first, $second) use ($u2) {

                  $second->loginAs($u2)->visit('/de/home')
                         ->assertPathIs('/de/home')
                         ->assertAuthenticated()
                         ->assertAuthenticatedAs($u2);
              });
    }
}
