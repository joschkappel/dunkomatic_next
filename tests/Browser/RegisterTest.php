<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class RegisterTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

    /**
     * test registration
     * @test
     * @group authx
     *
     * @return void
     */
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/de')
                    ->assertSee('HBV')
                    ->screenshot('start');

            if ($browser->seeLink('Registrieren')) {
              $browser->clickLink('Registrieren')
                      ->assertPathIs('/de/register')
                      ->assertSee('Konto zu registrieren')
                      ->type('name','tester')
                      ->type('email','test@gmail.com')
                      ->type('password','password')
                      ->type('password_confirmation','password')
                      ->type('reason_join','am testing')
                      ->select2('.sel-region')
                      ->screenshot('Registered_user')
                      ->press('Registrieren')
                      ->assertPathIs('/de/email/verify')
                      ->assertSee('Dein Account muss noch bes');
            }
        });

        $this->assertDatabaseHas('users', ['name' => 'tester']);


    }
}
