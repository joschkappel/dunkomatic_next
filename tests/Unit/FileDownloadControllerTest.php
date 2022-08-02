<?php

namespace Tests\Unit;

use App\Enums\ReportFileType;
use App\Models\Club;
use App\Models\League;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Tests\TestCase;
use Tests\Support\Authentication;

class FileDownloadControllerTest extends TestCase
{
    use Authentication;

    private $testleague;
    private $testclub_assigned;
    private $testclub_free;

    public function setUp(): void
    {
        parent::setUp();
        $this->testleague = League::factory()->registered(4, 4)->create();
        $this->testclub_assigned = $this->testleague->clubs()->first();
        $this->testclub_free = Club::whereNotIn('id', $this->testleague->clubs->pluck('id'))->first();
    }

    /**
     * get_file_club
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_file_club()
    {
        $path = $this->testclub_assigned->region->club_folder;
        UploadedFile::fake()->create('test.csv')
            ->storeAs($path, 'test.csv');

        $response = $this->authenticated()
            ->get(route('file.get', ['type' => Club::class, 'club' => $this->testclub_assigned, 'file' => 'test.csv']));

        $response->assertStatus(200);
            //->assertDownload('test.csv');
    }
    /**
     * get_file_league
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_file_league()
    {
        $path = $this->testleague->region->league_folder;

        $file = UploadedFile::fake()->create('test.csv')
            ->storeAs($path, 'test.csv');

        $response = $this->authenticated()
            ->get(route('file.get', ['type' => League::class, 'league' => $this->testleague, 'file' => 'test.csv']));

        $response->assertStatus(200);
            //->assertDownload('test.csv');
    }
    /**
     * get_user_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_user_archive()
    {

        Bouncer::assign('regionadmin')->to($this->region_user);
        Bouncer::allow($this->region_user)->to('manage', $this->region_user);
        Bouncer::allow($this->region_user)->to('access', $this->testleague->region);
        Bouncer::allow($this->region_user)->to('access', $this->testleague);
        Bouncer::allow($this->region_user)->to('access', $this->testclub_assigned);
        Bouncer::refresh();

        // check no file found

        $response = $this->authenticated()
            ->get(route('user_archive.get', ['region' => $this->testleague->region, 'user' => $this->region_user]));

        $response->assertStatus(404);

        // no create files
        $archive = $this->region->code . '-reports-' . Str::slug($this->region_user->name, '-') . '.zip';
        $folder = $this->testclub_assigned->region->club_folder;
        $filename = $this->testclub_assigned->shortname . '.test';
        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);
        $folder = $this->testleague->region->league_folder;
        $filename = $this->testleague->shortname . '.test';
        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('user_archive.get', ['region' => $this->testleague->region, 'user' => $this->region_user]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
    /**
     * get_club_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_club_archive()
    {
        // check no file found
        $response = $this->authenticated()
            ->get(route('club_archive.get', ['club' => $this->testclub_assigned, 'format' => ReportFileType::HTML]));

        $response->assertSessionHasErrors();

        // now create files
        $folder = $this->testclub_assigned->region->club_folder;
        $filename = $this->testclub_assigned->shortname . '.test.html';
        $archive = $this->testclub_assigned->region->code . '-reports-' . Str::slug($this->testclub_assigned->shortname, '-') . '.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('club_archive.get', ['club' => $this->testclub_assigned, 'format' => ReportFileType::HTML]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
    /**
     * get_league_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_league_archive()
    {
        // check no file found
        $response = $this->authenticated()
            ->get(route('league_archive.get', ['league' => $this->testleague, 'format' => ReportFileType::HTML]));

        $response->assertSessionHasErrors();

        // now create files
        $folder = $this->testleague->region->league_folder;
        $filename = $this->testleague->shortname . '.test.html';
        $archive = $this->testleague->region->code . '-reports-' . Str::slug($this->testleague->shortname, '-') . '.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('league_archive.get', ['league' => $this->testleague, 'format' => ReportFileType::HTML]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
    /**
     * get_region_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_region_archive()
    {
        // check no file found
        $response = $this->authenticated()
            ->get(route('region_archive.get', ['region' => $this->testleague->region, 'format' => ReportFileType::ODS]));

        $response->assertSessionHasErrors();

        // now create files
        $folder = $this->testleague->region->league_folder;
        $filename = $this->testleague->region->code . '.test.html';
        $archive = $this->testleague->region->code . '-reports.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('region_archive.get', ['region' => $this->testleague->region, 'format' => ReportFileType::HTML]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
    /**
     * get_region_league_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_region_league_archive()
    {
        // now create files
        $folder = $this->testleague->region->league_folder;
        $filename = $this->testleague->shortname . '.test';
        $archive = $this->testleague->region->code . '-runden-reports.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('region_league_archive.get', ['region' => $this->testleague->region]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
    /**
     * get_region_teamware_archve
     *
     * @test
     * @group game
     * @group controller
     *
     * @return void
     */
    public function get_region_teamware_archive()
    {
        // check no file found
        $response = $this->authenticated()
            ->get(route('region_teamware_archive.get', ['region' => $this->testleague->region]));

        $response->assertSessionHasErrors();

        // now create a fil
        $folder = $this->testleague->region->teamware_folder;
        $filename = $this->testleague->shortname . '.csv';
        $archive = $this->testleague->region->code . '-teamware-reports.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('region_teamware_archive.get', ['region' => $this->testleague->region]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
}
