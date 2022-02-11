<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {backupfile : Filename of the dump file created by db:backup }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load a gzipped dump to the mysql database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filename = $this->argument('backupfile');

        if ( Storage::disk('backup')->exists($filename)){
            $filepath = Storage::disk('backup')->path($filename);

            $command = 'zcat '. $filepath.' | mysql --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE');
            $returnVar = NULL;
            $output = NULL;

            exec($command, $output, $returnVar);
            if ($returnVar == COMMAND::SUCCESS){
                $this->info('DB restore was successful!');
            } else {
                $this->error('Oops someting went wrong, DB not restored from file '.$filepath);
            }
        } else {
            $returnVar = COMMAND::FAILURE;
            $this->error('The backup file '.$filename.' does NOT exist in folder '.Storage::disk('backup')->path(''));
        }

        return $returnVar;

    }
}
