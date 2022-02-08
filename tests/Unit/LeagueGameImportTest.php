<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Game;
use App\Models\Gym;
use App\Models\Team;
use App\Models\Club;
use App\Models\Schedule;
use App\Traits\LeagueFSM;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class LeagueGameImportTest extends TestCase
{
    use Authentication, LeagueFSM;


    protected static $league;

    /**
     * export club games csv
     *
     * @test
     * @group league
     * @group game
     * @group import
     *
     * @return void
     */
    public function import_csv_notok()
    {
        static::$league = League::factory()->custom()->selected(4,4)->create();
        $this->open_freeze( static::$league );
        $this->close_freeze( static::$league );
        $club = static::$league->clubs->first();

        $name = 'LEAGUE_Heimspiele.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => static::$league]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(84, $errs);

    }

    /**
     * export club games csv
     *
     * @test
     * @group league
     * @group game
     * @group import
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $name = 'LEAGUE_Heimspiele.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => static::$league]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(84, $errs);

    }
    /**
     * db_cleanup
     *
     * @test
     * @group leaguemgmt_X
     *
     * @return void
     */
    public function db_cleanup()
    {
        /// clean up DB
        Game::whereNotNull('id')->delete();
        Gym::whereNotNull('id')->delete();
        Team::whereNotNull('id')->delete();
        foreach (Club::all() as $c) {
            $c->leagues()->detach();
            $members = $c->members;
            $c->members()->detach();
            $c->delete();
            foreach ($members as $m){
                $m->delete();
            }
        }
        $league = League::first();
        if (isset($league)) {
            $league->schedule->events()->delete();
            $league->delete();
        }

        $schedule = Schedule::first();
        if (isset($schedule)){
            if ($schedule->events()->exists()){
                $schedule->events()->delete();
            }
            $schedule->delete();
        }

        //League::whereNotNull('id')->delete();
        $this->assertDatabaseCount('leagues', 0)
            ->assertDatabaseCount('clubs', 0)
            ->assertDatabaseCount('teams', 0)
            ->assertDatabaseCount('games', 0);
    }
}
