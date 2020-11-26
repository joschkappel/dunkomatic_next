<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Schedule;
use App\Models\League;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
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

    /**
     * @test
     * @group league
     * @group db
     */
    public function create_league()
    {
        $u = User::regionadmin('HBVDA')->first();
        $league_code = 'LSX';
        $league_code_new = 'LSY';
        $league_name = 'Runde XXX';
        $league_name_new = 'Neue Runde XXX';

        Schedule::factory()->count(3)->create();
        $schedule = Schedule::where('region_id','HBVDA')->first();
        $this->assertDatabaseHas('schedules', ['id' => $schedule->id]);

        $this->browse(function ($browser) use ($u, $league_code, $league_code_new, $league_name, $league_name_new) {
          $browser->loginAs($u)
                  ->visit(new NewLeague)
                  ->create_league($league_code, $league_name);

          $this->assertDatabaseHas('leagues', ['shortname' => $league_code]);
          $league = League::first();

          $browser->visit(new EditLeague($league->id))
                  ->modify_league($league_code_new, $league_name_new)
                  ->screenshot('Geänderte_runde');

          $this->assertDatabaseHas('leagues', ['shortname' => $league_code_new]);

        });

    }

}
