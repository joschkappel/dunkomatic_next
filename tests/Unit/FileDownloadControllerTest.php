<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Models\League;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use Tests\TestCase;
use Tests\Support\Authentication;

class FileDownloadControllerTest extends TestCase
{
    use Authentication;

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
        $path = static::$testclub->region->club_folder;

        $file = UploadedFile::fake()->create('test.csv')
            ->storeAs($path, 'test.csv');

        $response = $this->authenticated()
            ->get(route('file.get', ['type' => Club::class, 'club' => static::$testclub, 'file' => 'test.csv']));

        $response->assertStatus(200)
            ->assertDownload('test.csv');
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
        $path = static::$testleague->region->league_folder;

        $file = UploadedFile::fake()->create('test.csv')
            ->storeAs($path, 'test.csv');

        $response = $this->authenticated()
            ->get(route('file.get', ['type' => League::class, 'league' => static::$testleague, 'file' => 'test.csv']));

        $response->assertStatus(200)
            ->assertDownload('test.csv');
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
        $filename = $this->region->code . '-reports-' . Str::slug($this->region_user->name, '-') . '.zip';

        UploadedFile::fake()->create($filename);

        $response = $this->authenticated()
            ->get(route('user_archive.get', ['region' => $this->region, 'user' => $this->region_user]));

        $response->assertStatus(404);
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
        $folder = static::$testclub->region->club_folder;
        $filename = static::$testclub->shortname . '.test';
        $archive = static::$testclub->region->code . '-reports-' . Str::slug(static::$testclub->shortname, '-') . '.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('club_archive.get', ['club' => static::$testclub]));

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
        $folder = static::$testleague->region->league_folder;
        $filename = static::$testleague->shortname . '.test';
        $archive = static::$testleague->region->code . '-reports-' . Str::slug(static::$testleague->shortname, '-') . '.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('league_archive.get', ['league' => static::$testleague]));

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
        $folder = static::$testleague->region->league_folder;
        $filename = static::$testleague->shortname . '.test';
        $archive = static::$testleague->region->code . '-runden-reports.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('region_league_archive.get', ['region' => static::$testleague->region]));

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
        $folder = static::$testleague->region->teamware_folder;
        $filename = static::$testleague->shortname . '.test';
        $archive = static::$testleague->region->code . '-teamware-reports.zip';

        UploadedFile::fake()->create($filename)
            ->storeAs($folder, $filename);

        $response = $this->authenticated()
            ->get(route('region_teamware_archive.get', ['region' => static::$testleague->region]));

        $response->assertStatus(200)
            ->assertDownload($archive);
    }
}
