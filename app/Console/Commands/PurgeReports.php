<?php

namespace App\Console\Commands;

use App\Models\Region;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dmatic:purge-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge old versions of reports and move to the archive.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        foreach (Region::all() as $region) {
            // main folder
            $this->removeOldFileVersions($region->region_folder, $region->code, 'Rundenbuch', 'html');
            // league folder
            // club folder
        }
        return Command::SUCCESS;
    }

    function removeOldFileVersions($folder, $prefix, $name, $ext)
    {
        $files = Storage::files($folder); // Get a list of all files in the storage directory


        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $fileext = pathinfo($file, PATHINFO_EXTENSION);
            $fileParts = explode('_', $filename);
            // $this->info($filename);

            if (count($fileParts) === 4 && $fileParts[0] === $prefix && $fileParts[1] === $name && $fileext === $ext) {
                $versionDates = [
                    substr($fileParts[2], 1, 6), // Extract the first date from the version
                    $fileParts[3],    // Extract the second date from the version
                ];

                // Convert the version dates to DateTime objects for comparison
                $date1 = DateTime::createFromFormat('ymd', $versionDates[0]);
                $date2 = DateTime::createFromFormat('ymd', $versionDates[1]);

                // Calculate the difference between the version dates
                $dateDifference = $date1->diff($date2);

                // Define a threshold for how old the version can be (e.g., 30 days)
                $maxDaysDifference = 0;

                if ($dateDifference->days > $maxDaysDifference) {
                    // Storage::delete($file); // Delete the file
                    $this->info("Deleted: $file");
                    $this->newLine();
                }
            }
        }
    }
}
