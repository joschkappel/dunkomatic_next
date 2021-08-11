<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Database\Seeders\TestDatabaseSeeder;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        //$this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        $this->seed(TestDatabaseSeeder::class);

    }
    /**
     * A Dusk test example.
     * @test
     * @group auth
     *
     * @return void
     */
    public function testApproveWaiting()
    {

        $u1 = User::where('name','notapproved')->first();

        $this->browse(function (Browser $first) use ($u1) {
                  $first->visit('/de/login')
                          ->screenshot('login')
                          ->type('email', $u1->email)
                          ->type('password', 'password')
                          ->click('@login')
                          ->assertPathBeginsWith('/de')
                          ->assertPathIs('/de/approval')
                          ->screenshot('approval');

        });
    }
}
