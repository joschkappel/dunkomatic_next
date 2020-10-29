<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\User;
use App\Models\Member;
use App\Models\Region;


class RegisterTest extends DuskTestCase
{

    use DatabaseMigrations;
    protected $region = 'HBVDA';
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        // create region admin
        $this->user = User::factory()->create([
                  'email' => 'taylor3@laravel.com',
                  'region' => $this->region,
                  'regionadmin' => True,
                  'approved_at' => now(),
              ]);
        $this->member = Member::factory()->create([
                        'email1' => 'taylor3@laravel.com',
                        'user_id' => $this->user->id,
                      ]);

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
                      ->assertSee('Dein Account muss noch bestätigt werden');
            }
        });

        $newuser = User::where('name','tester')->first();

    }
}