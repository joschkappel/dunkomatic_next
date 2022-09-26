<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use App\Traits\LeagueFSM;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Authentication;
use Tests\TestCase;

class ClubGameControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

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
     * chart
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function chart()
    {
        $response = $this->authenticated()
            ->get(route('club.game.chart', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('club.club_hgame_chart')
            ->assertViewHas('club', $this->testclub_assigned);
    }

    /**
     * list_Home
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function list_home()
    {
        $response = $this->authenticated()
            ->get(route('club.game.list_home', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200);
    }

    /**
     * chart_Home
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function chart_home()
    {
        $response = $this->authenticated()
            ->get(route('club.game.chart_home', ['club' => $this->testclub_assigned]));

        $response->assertStatus(200);
    }

    /**
     * upload
     *
     * @test
     * @group club
     * @group controller
     *
     * @return void
     */
    public function upload()
    {
        $response = $this->authenticated()
            ->get(route('club.upload.homegame', ['language' => 'de', 'club' => $this->testclub_assigned]));

        $response->assertStatus(200)
            ->assertViewIs('game.game_file_upload')
            ->assertViewHas('context', 'club');
    }

    /**
     * export club games csv
     *
     * @test
     * @group club
     * @group game
     * @group import
     *
     * @return void
     */
    public function import_csv_notok()
    {
        $this->refreeze_league($this->testleague);
        $this->open_game_scheduling($this->testleague);
        $club = $this->testleague->clubs->first();

        $name = 'CLUB_Heimspiele.csv';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'text/csv', null, true);

        $response = $this->authenticated()
            ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile' => $file]);

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
     * @group import
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $club = $this->testleague->clubs->first();

        $name = 'CLUB_Heimspiele.xlsx';
        $stub = __DIR__.'/stubs/'.$name;
        Storage::disk('local')->makeDirectory('importtest');
        $filename = Storage::putFileAs('importtest', new File($stub), $name);
        $path = Storage::disk('local')->path($filename);

        $file = new UploadedFile($path, $name, 'Excel/xlsx', null, true);

        $response = $this->authenticated()
            ->postJson(route('club.import.homegame', ['language' => 'de', 'club' => $club]), ['gfile' => $file]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrorsIn('ebag');

        $errs = $response->getSession()->get('errors')->getBag('default');
        $this->assertCount(15, $errs);
    }
}
