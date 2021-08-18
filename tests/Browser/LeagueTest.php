<?php

namespace Tests\Browser;

use App\Models\Region;
use App\Models\Schedule;
use App\Models\League;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Tests\Browser\Pages\League\NewLeague;
use Tests\Browser\Pages\League\EditLeague;

class LeagueTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }

    /**
     * @test
     * @group league
     * @group db
     */
    public function create_league()
    {

        $r = Region::where('code','HBVDA')->first();
        $u = $r->regionadmin->first()->user()->first();
        $league_code = 'LSX';
        $league_code_new = 'LSY';
        $league_name = 'Runde XXX';
        $league_name_new = 'Neue Runde XXX';

        Schedule::factory()->count(3)->create();
        $schedule = Schedule::where('region_id',$r->id)->first();
        $this->assertDatabaseHas('schedules', ['id' => $schedule->id]);
        $this->assertDatabaseHas('league_sizes', ['description' => '4 Teams']);

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
