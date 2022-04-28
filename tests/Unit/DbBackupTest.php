<?php

namespace Tests\Unit;

use App\Jobs\ProcessFilesCleanup;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;
use Tests\Support\Authentication;

class DbBackupTest extends TestCase
{
    use Authentication;

    /**
     * backup
     *
     * @test
     * @group command
     *
     * @return void
     */
    public function backup()
    {

        $backup_folder = $backup_folder = config('dunkomatic.folders.backup');
        // Get all files in a directory
        $files =  Storage::disk('local')->allFiles($backup_folder);

        // Delete Files
        Storage::disk('local')->delete($files);

        $this->artisan('db:backup')->assertSuccessful();

        $files =  Storage::disk('local')->allFiles($backup_folder);
        $this->assertCount(1, $files);

    }

    /**
     * file_rotation
     *
     * @test
     * @group command
     *
     * @return void
     */
    public function file_rotation()
    {

        $backup_folder = $backup_folder = config('dunkomatic.folders.backup');
        // Get all files in a directory
        $files =  Storage::disk('local')->allFiles($backup_folder);

        // Delete Files
        Storage::disk('local')->delete($files);

        // create 1 backup file
        $this->artisan('db:backup')->assertSuccessful();

        // get all files in folder
        $files =  Storage::disk('local')->allFiles($backup_folder);
        $this->assertCount(1, $files);

        // now run file rotation, as file is new it should stay
        $job_instance = resolve( ProcessFilesCleanup::class);
        app()->call([$job_instance, 'handle']);
        $files =  Storage::disk('local')->allFiles($backup_folder);
        $this->assertCount(1, $files);

        // now move to the future < 90 days and run again, still 1 files expected
        $this->travel( config('dunkomatic.db_backup_age',90)-2 )->days();

        $job_instance = resolve( ProcessFilesCleanup::class);
        app()->call([$job_instance, 'handle']);
        $files =  Storage::disk('local')->allFiles($backup_folder);
        $this->assertCount(1, $files);

        // now move to the future > 90 days and run again, file should be gone
        $this->travelBack();
        $this->travel( config('dunkomatic.db_backup_age',90)+1 )->days();

        $job_instance = resolve( ProcessFilesCleanup::class);
        app()->call([$job_instance, 'handle']);
        $files =  Storage::disk('local')->allFiles($backup_folder);
        $this->assertCount(0, $files);

        $this->travelBack();
    }

}
