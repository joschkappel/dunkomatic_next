<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Club;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Browser\Pages\Club\NewClub;
use Tests\Browser\Pages\Club\EditClub;
use TestDatabaseSeeder;

class ClubTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    use withFaker;
    /**
     * @test
     * @group club
     * @group db
     */
    public function create_club()
    {
        $u = User::regionadmin('HBVDA')->first();
        $club_no = '1234567';
        $club_no2 = '1122334';

        $this->browse(function ($browser) use ($u, $club_no, $club_no2) {
          $browser->loginAs($u)->visit('/de/club')
                  ->assertSee('Vereinsliste')
                  ->clickLink('Neuer Verein')
                  ->on(new NewClub)
                  ->assertSee('Neuen Verein')
                  ->type('region','HBVDA')
                  ->type('shortname','VVVV')
                  ->type('name','Verein VVV')
                  ->type('club_no',$club_no)
                  ->type('url', $this->faker->url)
                  ->press('Senden');
          });

          $this->assertDatabaseHas('clubs', ['club_no' => $club_no]);;

          $club = Club::where('shortname','VVVV')->first();

          $this->browse(function ($browser) use ($u, $club_no, $club_no2, $club) {
            $browser->visit('/de/club')
                  ->waitUntil('!$.active')
                  ->assertSee('VVVV')
                  ->clickLink('VVVV')
                  ->assertPathIs('/de/club/'.$club->id.'/list')
                  ->clickLink('Vereinsdaten ändern')
                  ->on(new EditClub($club->id))
                  ->assertSee('Ändere die Vereinsdaten')
                  ->type('club_no',$club_no2)
                  ->type('name','Verein VVVXXX')
                  ->press('Senden')
                  ->assertPathIs('/de/club/'.$club->id.'/list')
                  ->assertSee('VVVXXX');
          });

          $this->assertDatabaseHas('clubs', ['club_no' => $club_no2]);


    }

}
