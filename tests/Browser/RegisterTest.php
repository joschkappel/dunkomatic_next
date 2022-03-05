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
                    ->assertSee('HESSISCHER BASKETBALL VERBAND')
                    ->screenshot('start');

            if ($browser->seeLink('Registrieren')) {
              $browser->clickLink('Registrieren')
                      ->assertPathIs('/de/signup')
                      ->assertSee('oder benutze deine eMail')
                      ->assertSee('Anbieter registrieren')
                      ->click('@register-button')
                      ->assertPathIs('/de/register')
                      ->assertSee('lege dein Benutzerkonto an')
                      ->type('name','tester')
                      ->type('email','test@gmail.com')
                      ->type('password','password')
                      ->type('password_confirmation','password')
                      ->type('reason_join','am testing')
                      ->type('captcha','12345')
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
