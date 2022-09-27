<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Authentication;
use Tests\TestCase;

class LeagueGameImportTest extends TestCase
{
    use Authentication;

    private $testleague;

    private $testclub_assigned;

    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->frozen(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
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
    public function import_csv_notok()
    {
        $name = 'LEAGUE_Heimspiele.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => $this->testleague]), ['gfile' => $file]);

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
            ->postJson(route('league.import.game', ['language' => 'de', 'league' => $this->testleague]), ['gfile' => $file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(84, $errs);
    }
}
