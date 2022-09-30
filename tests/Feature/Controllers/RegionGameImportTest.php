<?php

namespace Tests\Feature\Controllers;

use App\Models\Club;
use App\Models\League;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Authentication;
use Tests\TestCase;

class RegionGameImportTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(3, 3)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

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
        $region = $this->testleague->region;

        $name = 'REGION_Bezirksspielplan.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $region]), ['gfile' => $file]);

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
        $region = $this->testleague->region;
        $name = 'REGION_Bezirksspielplan.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('region.import.refgame', ['language' => 'de', 'region' => $region]), ['gfile' => $file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(14, $errs);
    }
}
