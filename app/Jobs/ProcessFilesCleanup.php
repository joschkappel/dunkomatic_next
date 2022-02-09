<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessFilesCleanup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // delete old DB backups
        collect(Storage::disk('local')->files('backup'))
        ->each(function($file) {
            if ( (Str::contains($file, 'backup-dunkomatic_next')) and (Storage::lastModified($file) < now()->subDays( config('dunkomatic.db_backup_age',90)   )->getTimestamp())) {
                Storage::disk('local')->delete($file);
                Log::debug('[JOB][FILES CLEANUP] db backup deleted',['name'=>$file]);
            }
        });
        Log::notice('[JOB][FILES CLEANUP] DB backups removed all files older than '.config('dunkomatic.db_backup_age',90) .' days');

        // empty temp folder


        // emtpy upload folder

        // empty exports folder

    }
}
