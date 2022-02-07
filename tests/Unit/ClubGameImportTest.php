<?php

namespace Tests\Unit;

use App\Models\League;
use App\Traits\LeagueFSM;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class ClubGameImportTest extends TestCase
{
    use Authentication, LeagueFSM;


    protected static $league;

    /**
     * export club games csv
     *
     * @test
     * @group club
     * @group game
     * @group importx
     *
     * @return void
     */
    public function import_csv_notok()
    {
        static::$league = League::factory()->selected(4,4)->create();
        $this->open_freeze( static::$league );
        $this->close_freeze( static::$league );
        $club = static::$league->clubs->first();

        $name = 'CLUB_Heimspiele.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(15, $errs);

    }

    /**
     * export club games csv
     *
     * @test
     * @group club
     * @group game
     * @group importx
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $club = static::$league->clubs->first();

        $name = 'CLUB_Heimspiele.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(15, $errs);

    }
}
