<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use TestDatabaseSeeder;

class LoginApprovedTest extends DuskTestCase
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
    public function testApproved()
    {

        $u2 = User::where('name','approved')->first();

        $this->browse(function (Browser $second) use ($u2) {
                  $second->loginAs($u2)->visit('/de/home')
                         ->assertPathIs('/de/home')
                         ->assertAuthenticatedAs($u2);
        });
    }
}
