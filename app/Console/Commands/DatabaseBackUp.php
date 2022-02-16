<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DatabaseBackUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes a gzipped dump of the mysql database';

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
        $filename = 'backup-' . env('DB_DATABASE').'-'.Carbon::now()->format('Y-m-d-His') . '.gz';
        $filepath = Storage::disk('backup')->path($filename);

        $command = 'mysqldump --column-statistics=0 --user='.env('DB_USERNAME').' --password='.env('DB_PASSWORD').' --host='.env('DB_HOST').' '.env('DB_DATABASE').' | gzip > '. $filepath;
        $returnVar = NULL;
        $output = NULL;

        exec($command, $output, $returnVar);

        if ($returnVar == COMMAND::SUCCESS){
            $this->info('DB backup was successful!');
            $this->line('Backup file saved to '.$filepath);
        } else {
            $this->error('Oops someting went wrong, DB not backed up!');
        }

        return $returnVar;
    }
}
