<?php

namespace Tests\Unit;

use App\Traits\LeagueFSM;
use App\Enums\LeagueState;

use Tests\TestCase;
use Tests\Support\Authentication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;

class ClubGameControllerTest extends TestCase
{
    use Authentication, LeagueFSM;

    public function setUp(): void
    {
        static::$state = LeagueState::Freeze;
        static::$initial_clubs = 4;
        static::$initial_teams = 4;
        parent::setUp();
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
            ->get(route('club.game.chart', ['language'=>'de', 'club' => static::$testclub]));

        $response->assertStatus(200)
            ->assertViewIs('club.club_hgame_chart')
            ->assertViewHas('club', static::$testclub);
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
            ->get(route('club.game.list_home', ['language'=>'de', 'club' => static::$testclub]));

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
            ->get(route('club.game.chart_home', ['club' => static::$testclub]));

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
            ->get(route('club.upload.homegame', ['language'=>'de', 'club' => static::$testclub]));

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

        $this->open_freeze( static::$testleague );
        $this->close_freeze( static::$testleague );
        $club = static::$testleague->clubs->first();

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
     * @group import
     *
     * @return void
     */
    public function import_xlsx_notok()
    {
        $club = static::$testleague->clubs->first();

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
