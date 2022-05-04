<?php

namespace Tests\Browser;

use App\Models\League;
use App\Traits\LeagueFSM;
use App\Models\User;
use App\Models\Region;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\WithFaker;


class GameExportTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected static $league;
    protected static $user;
    protected static $region;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'TestDatabaseSeeder']);
        static::$league = League::factory()->selected(4,4)->create();
        $this->refreeze_league( static::$league );
        $this->open_game_scheduling( static::$league );

        static::$region = Region::where('code','HBVDA')->first();
        static::$user = User::factory()->approved()->create();
        Bouncer::retract( static::$user->getRoles()  )->from(static::$user);
        Bouncer::assign( 'superadmin')->to(static::$user);
        Bouncer::refreshFor(static::$user);
    }

    use withFaker, LeagueFSM;

    /**
     * @test
     * @group export
     * @group league
     * @group game
     */
    public function export_leaguegame_csv()
    {

        $this->assertDatabaseCount('leagues', 1);
        $this->assertDatabaseCount('clubs', 4);
        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('games', 12);

        $user = static::$user;
        $league = static::$league;

        $this->browse(function ($browser) use ($user, $league) {

            $browser->loginAs($user)
                    ->visitRoute('league.dashboard',['language'=>'de', 'league'=>$league])
                    ->screenshot('league_game_export_league_dashboard');

            $browser->with('#gamesCard', function ($gamesCard)  {
                $gamesCard->click('.btn-tool')
                          ->waitFor('.btn-tool')
                          ->assertSeeLink(__('league.action.game.list'))
                          ->clickLink(__('league.action.game.list'))
                          ->screenshot('league_game_export_league_game_list')
                          ->waitFor('#table');
            });
            $browser->assertSee('Export')
                    ->press('Export')
                    ->waitForText('CSV')
                    ->assertSeeLink('CSV')
                    ->clickLink('CSV')
                    ->assertSeeLink('Excel')
                    ->clickLink('Excel')
                    ->assertDontSee('Import (.csv, .xlsx)')
                    ->screenshot('league_game_export_league_game_list2');
        });
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$league->shortname.'_Heimspiele.csv');
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$league->shortname.'_Heimspiele.xlsx');

        // remove downloaded files
        unlink( __DIR__.'/Exportfiles/'.$league->shortname.'_Heimspiele.csv');
        unlink( __DIR__.'/Exportfiles/'.$league->shortname.'_Heimspiele.xlsx');

    }

    /**
     * @test
     * @group export
     * @group club
     * @group game
     */
    public function eximport_clubgame()
    {

        $this->assertDatabaseCount('leagues', 1);
        $this->assertDatabaseCount('clubs', 4);
        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('games', 12);

        $user = static::$user;
        $league = static::$league;
        $club = $league->clubs->first();

        $this->browse(function ($browser) use ($user, $club) {

            $browser->loginAs($user)
                    ->visitRoute('club.dashboard',['language'=>'de', 'club'=>$club])
                    ->screenshot('club_game_export_club_dashboard');

            $browser->with('#gamesCard', function ($gamesCard)  {
                $gamesCard->click('.btn-tool')
                          ->waitFor('.btn-tool')
                          ->assertSeeLink(__('club.action.edit-homegame'))
                          ->clickLink(__('club.action.edit-homegame'))
                          ->screenshot('club_game_export_club_game_list')
                          ->waitFor('#table');
            });
            $browser->assertSee('Export')
                    ->press('Export')
                    ->waitForText('CSV')
                    ->assertSeeLink('CSV')
                    ->clickLink('CSV')
                    ->assertSeeLink('Excel')
                    ->clickLink('Excel')
                    ->screenshot('club_game_export_club_game_list2');
        });
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.csv');
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.xlsx');

        $this->browse(function ($browser) use ($user, $club) {

            $browser->loginAs($user)
                    ->visitRoute('club.dashboard',['language'=>'de', 'club'=>$club])
                    ->screenshot('club_game_export_club_dashboard');

            $browser->with('#gamesCard', function ($gamesCard)  {
                $gamesCard->click('.btn-tool')
                          ->waitFor('.btn-tool')
                          ->assertSeeLink(__('club.action.edit-homegame'))
                          ->clickLink(__('club.action.edit-homegame'))
                          ->screenshot('club_game_export_club_game_list')
                          ->waitFor('#table');
            });
            $browser->assertSee('Import (.csv, .xlsx)')
                    ->press('Import (.csv, .xlsx)')
                    ->assertPathIs('/de/club/'.$club->id.'/game/upload')
                    ->attach('gfile', __DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.csv')
                    ->screenshot('club_game_import_club_game_list2')
                    ->press('Senden')
                    ->waitFor('.alert-success')
                    ->screenshot('club_game_import_club_game_list3')
                    ->press('Reset')
                    ->attach('gfile', __DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.xlsx')
                    ->screenshot('club_game_import_club_game_list4')
                    ->press('Senden')
                    ->waitFor('.alert-success')
                    ->screenshot('club_game_import_club_game_list5');;

        });

        // remove downloaded files
        unlink( __DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.csv');
        unlink( __DIR__.'/Exportfiles/'.$club->shortname.'_Heimspiele.xlsx');
    }

    /**
     * @test
     * @group export
     * @group region
     * @group game
     */
    public function eximport_regiongame()
    {

        $this->assertDatabaseCount('leagues', 1);
        $this->assertDatabaseCount('clubs', 4);
        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('games', 12);

        $user = static::$user;
        $league = static::$league;
        $region = $league->region;

        $this->browse(function ($browser) use ($user, $region) {

            $browser->loginAs($user)
                    ->visitRoute('region.dashboard',['language'=>'de', 'region'=>$region])
                    ->screenshot('region_game_export_region_dashboard');

            $browser->with('#refereeCard', function ($refCard)  {
                $refCard->click('.btn-tool')
                          ->waitFor('.btn-tool')
                          ->assertSeeLink(__('game.action.assign-referees'))
                          ->clickLink(__('game.action.assign-referees'))
                          ->screenshot('region_game_export_region_game_list')
                          ->waitFor('#table');
            });
            $browser->assertSee('Export')
                    ->press('Export')
                    ->waitForText('CSV')
                    ->assertSeeLink('CSV')
                    ->clickLink('CSV')
                    ->assertSeeLink('Excel')
                    ->clickLink('Excel')
                    ->screenshot('region_game_export_region_game_list2');
        });
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.csv');
        $this->assertFileExists(__DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.xlsx');
        $this->browse(function ($browser) use ($user, $region) {

            $browser->loginAs($user)
                    ->visitRoute('region.dashboard',['language'=>'de', 'region'=>$region])
                    ->screenshot('region_game_export_region_dashboard');

            $browser->with('#refereeCard', function ($refCard)  {
                $refCard->click('.btn-tool')
                          ->waitFor('.btn-tool')
                          ->assertSeeLink(__('game.action.assign-referees'))
                          ->clickLink(__('game.action.assign-referees'))
                          ->screenshot('region_game_export_region_game_list')
                          ->waitFor('#table');
            });
            $browser->assertSee('Import (.csv, .xlsx)')
                    ->press('Import (.csv, .xlsx)')
                    ->assertPathIs('/de/region/'.$region->id.'/game/upload')
                    ->attach('gfile', __DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.csv')
                    ->screenshot('region_game_import_region_game_list2')
                    ->press('Senden')
                    ->waitFor('.alert-success')
                    ->screenshot('region_game_import_region_game_list3')
                    ->press('Reset')
                    ->attach('gfile', __DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.xlsx')
                    ->screenshot('region_game_import_region_game_list4')
                    ->press('Senden')
                    ->waitFor('.alert-success')
                    ->screenshot('region_game_import_region_game_list5');;

        });


        // remove downloaded files
        unlink( __DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.csv');
        unlink( __DIR__.'/Exportfiles/'.$region->code.'_Bezirksspielplan.xlsx');
    }
}
