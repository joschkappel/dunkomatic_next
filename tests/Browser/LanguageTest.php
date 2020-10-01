<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LanguageTest extends DuskTestCase
{
  use DatabaseMigrations;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed --class=TestDatabaseSeeder');
        $this->user = User::factory()->create([
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
    public function testLanguageSwitch()
    {
        $u = $this->user;
        $this->browse(function ($browser) use ($u) {

            $browser->loginAs($u)->visit('/de/home')
                    ->assertPathIs('/de/home');

            // switch to english
            if ($browser->seeLink('EN')) {
              $browser->clickLink('EN')
                      ->assertPathIs('/en/home');
            }

            //switch back to german
            $browser->clickLink('DE')
                    ->assertPathIs('/de/home');
        });
    }
}
