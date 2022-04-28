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
        $db = config('database.connections.dunknxt.database');
        $db_usr = config('database.connections.dunknxt.username');
        $db_pwd = config('database.connections.dunknxt.password');
        $db_host = config('database.connections.dunknxt.host');
        $backup_folder = config('dunkomatic.folders.backup');

        $filename = 'backup-' . $db .'-'.Carbon::now()->format('Y-m-d-His') . '.gz';
        $filepath = Storage::disk('local')->path('tmp/'.$filename);

        $command = 'mysqldump --column-statistics=0 --user='.$db_usr.' --password='.$db_pwd.' --host='.$db_host.' '.$db.' | gzip > '. $filepath;
        $returnVar = NULL;
        $output = NULL;

        exec($command, $output, $returnVar);

        if ($returnVar == COMMAND::SUCCESS){
            $stream = Storage::disk('local')->readStream('tmp/'.$filename);
            $target = $backup_folder . '/' . $filename;
            if ( Storage::disk('local')->writeStream( $target, $stream ) ){
                $this->info('DB backup to '.$target.' was successful!');
            } else {
                $this->error('Oops someting went wrong, could not move the file to storage disk!');
            }

        } else {
            $this->error('Oops someting went wrong, DB not backed up!');
        }
        // remove file
        Storage::disk('local')->delete('tmp/'.$filename);

        return $returnVar;
    }
}
