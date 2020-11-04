<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use TestDatabaseSeeder;

class LanguageTest extends DuskTestCase
{
  use DatabaseMigrations;
    private $user;
    private $member;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);

    }
    /**
     * A Dusk test example.
     * @test
     * @group i18n
     *
     * @return void
     */
    public function testLanguageSwitch()
    {
        $u = User::regionadmin('HBVDA')->first();
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
