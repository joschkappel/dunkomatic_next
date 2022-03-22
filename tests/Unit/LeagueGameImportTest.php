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
        $this->open_freeze( static::$testleague );
        $this->close_freeze( static::$testleague );

        $name = 'LEAGUE_Heimspiele.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => static::$testleague]), ['gfile'=>$file]);

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
        $this->open_freeze( static::$testleague );
        $this->close_freeze( static::$testleague );

        $name = 'LEAGUE_Heimspiele.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => static::$testleague]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(84, $errs);

    }
}
