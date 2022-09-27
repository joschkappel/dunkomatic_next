<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginApprovedTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

    /**
     * A Dusk test example.
     *
     * @test
     * @group auth
     *
     * @return void
     */
    public function testApproved()
    {
        $u2 = User::where('name', 'approved')->first();

        $this->browse(function (Browser $second) use ($u2) {
            $second->loginAs($u2)->visit('/de/home')
                   ->assertPathIs('/de/home')
                   ->assertAuthenticatedAs($u2);
        });
    }
}
