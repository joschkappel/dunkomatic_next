<?php

namespace Tests\Unit;

use App\Models\League;
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
     * @group importx
     *
     * @return void
     */
    public function import_csv_notok()
    {
        static::$league = League::factory()->selected(4,4)->create();
        $this->open_freeze( static::$league );
        $this->close_freeze( static::$league );
        $region = static::$league->region;

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
        $this->assertCount(4, $errs);

    }

    /**
     * export club games csv
     *
     * @test
     * @group region
     * @group game
     * @group importx
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $region = static::$league->region;
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
        $this->assertCount(4, $errs);

    }
}
