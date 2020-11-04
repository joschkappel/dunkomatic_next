<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\User;
use App\Models\Member;
use App\Models\Region;
use Database\Seeders\TestDatabaseSeeder;


class RegisterTest extends DuskTestCase
{

    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    /**
     * test registration
     * @test
     * @group auth
     *
     * @return void
     */
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/de')
                    ->screenshot('start')
                    ->assertSee('DunkOmatic Next');

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
                      ->press('Registrieren')
                      ->screenshot('Registered_user')
                      ->assertPathIs('/de/email/verify')
                      ->assertSee('Dein Account muss noch bes');
            }
        });

        $newuser = User::where('name','tester')->first();

    }
}
