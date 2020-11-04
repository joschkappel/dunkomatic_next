<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Schedule;
use App\Models\League;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Browser\Pages\League\NewLeague;
use Tests\Browser\Pages\League\EditLeague;
use Database\Seeders\TestDatabaseSeeder;

class LeagueTest extends DuskTestCase
{
    use DatabaseMigrations;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    use withFaker;
    /**
     * @test
     * @group league
     * @group db
     */
    public function create_league()
    {
        $u = User::regionadmin('HBVDA')->first();
        $league_code = 'LSX';
        $league_code2 = 'LSY';

        Schedule::factory()->count(3)->create();
        $schedule = Schedule::where('region_id','HBVDA')->first();
        $this->assertDatabaseHas('schedules', ['id' => $schedule->id]);

        $this->browse(function ($browser) use ($u, $league_code, $league_code2,$schedule) {
          $browser->loginAs($u)->visit('/de/league')
                  ->assertSee('Rundenliste')
                  ->clickLink('Neue Runde')
                  ->on(new NewLeague)
                  ->assertSee('Neue Spielrunde')
                  ->assertSee('HBVDA')
                  ->type('shortname',$league_code)
                  ->type('name','Runde XXX')
                  ->select2('.js-sel-schedule')
                  ->screenshot('Neue_runde')
                  ->press('Senden');

          $this->assertDatabaseHas('leagues', ['shortname' => $league_code]);
          $league = League::first();

          $browser->visit('/de/league/'.$league->id.'/list')
                  ->clickLink('Rundendaten Ändern')
                  ->on(new EditLeague($league->id))
                  ->assertSee('Ändere die Daten der Spielrunde')
                  ->type('shortname',$league_code2)
                  ->type('name','Runde VVVXXX')
                  ->press('Senden')
                  ->assertPathIs('/de/league/'.$league->id.'/list')
                  ->screenshot('Geänderte_runde')
                  ->assertSee('LSY');

          $this->assertDatabaseHas('leagues', ['shortname' => $league_code2]);

        });

    }

}
