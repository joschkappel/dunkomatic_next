<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessFilesCleanup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // delete old DB backups
        $file_cnt = 0;
        collect(Storage::disk('local')->files( config('dunkomatic.folders.backup') ))
        ->each(function($file) use (&$file_cnt) {
            if ( ( Str::contains($file, 'backup-dunkomatic_next')) and
                 ( Storage::disk('local')->lastModified($file) < Carbon::now()->subDays( config('dunkomatic.db_backup_age',90)   )->getTimestamp())) {
                Storage::disk('local')->delete($file);
                Log::debug('[JOB][FILES CLEANUP] db backup deleted',['name'=>$file, 'date'=>Carbon::now()]);
                $file_cnt += 1;
            } else {
                Log::debug('[JOB][FILES CLEANUP] db backup found',['name'=>$file]);
            }
        });
        Log::notice('[JOB][FILES CLEANUP] DB backups removed '.$file_cnt.' files older than '.config('dunkomatic.db_backup_age',90) .' days');

        // empty temp folder


        // emtpy upload folder

        // empty exports folder

    }
}
