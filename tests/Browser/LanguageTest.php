<?php

namespace Tests\Browser;

use App\Models\Region;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;


class LanguageTest extends DuskTestCase
{
  use DatabaseMigrations;
    private $user;
    private $member;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);

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
        $r = Region::where('code','HBVDA')->first();
        $u = $r->regionadmin->first()->user()->first();

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
