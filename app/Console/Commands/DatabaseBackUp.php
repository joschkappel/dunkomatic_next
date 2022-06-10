<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

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
        Log::notice('[CMD] running db backup');
        $db = config('database.connections.dunknxt.database');
        $db_usr = config('database.connections.dunknxt.username');
        $db_pwd = config('database.connections.dunknxt.password');
        $db_host = config('database.connections.dunknxt.host');
        $backup_folder = config('dunkomatic.folders.backup');

        $filename = 'backup-' . $db .'-'.Carbon::now()->format('Y-m-d-His') . '.gz';
        $filepath = storage_path( 'app/'.$backup_folder.'/'.$filename);

        $command = 'mysqldump --column-statistics=0 --user='.$db_usr.' --password='.$db_pwd.' --host='.$db_host.' '.$db.' | gzip > '. $filepath;
        $returnVar = NULL;
        $output = NULL;

        try {
            exec($command, $output, $returnVar);

            if ($returnVar == COMMAND::SUCCESS){
                Log::notice('[CMD] db backup ran successfull',['file'=>$filepath]);
                Log::info($output);
            } else {
                Log::error('[CMD] db backup failed');
                $this->error('Oops someting went wrong, DB not backed up!');
            }
        } catch (Exception $exception) {
            $this->error('The backup process has failed.');
            $this->line($exception->getMessage());
            $this->line($exception->getTraceAsString());
        }

        return $returnVar;
    }
}
