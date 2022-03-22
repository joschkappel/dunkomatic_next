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

class RegionGameImportTest extends TestCase
{
    use Authentication, LeagueFSM;


    protected static $league;

    /**
     * export club games csv
     *
     * @test
     * @group region
     * @group game
     * @group import
     *
     * @return void
     */
    public function import_csv_notok()
    {
        $this->open_freeze( static::$testleague );
        $this->close_freeze( static::$testleague );
        $region = static::$testleague->region;

        $name = 'REGION_Bezirksspielplan.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $region]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(14, $errs);

    }

    /**
     * export club games csv
     *
     * @test
     * @group region
     * @group game
     * @group import
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $region = static::$testleague->region;
        $name = 'REGION_Bezirksspielplan.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $region]), ['gfile'=>$file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(14, $errs);

    }


}
