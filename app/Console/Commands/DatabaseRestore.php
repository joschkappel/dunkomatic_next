<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {backupfile=select : Filename of the dump file created by db:backup }';

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
        $db = config('database.connections.dunknxt.database');
        $db_usr = config('database.connections.dunknxt.username');
        $db_pwd = config('database.connections.dunknxt.password');
        $db_host = config('database.connections.dunknxt.host');
        $backup_folder = config('dunkomatic.folders.backup').'/';

        $filename = $this->argument('backupfile');
        if ($filename == 'select') {
            $this->components->info('Searching backup folder...');
            $files = Storage::disk('local')->files($backup_folder);
            $archivefile = $this->components->choice(
                'Which backup do you want to restore?',
                $files,
                count($files) - 1
            );
            $filename = Str::of($archivefile)->explode('/')->last();
        }

        if (Storage::disk('local')->exists($backup_folder.$filename)) {
            $filepath = storage_path('app/'.$backup_folder.$filename);
            $command = 'zcat '.$filepath.' | mysql --user='.$db_usr.' --password='.$db_pwd.' --host='.$db_host.' '.$db;
            $returnVar = null;
            $output = null;

            exec($command, $output, $returnVar);
            if ($returnVar == COMMAND::SUCCESS) {
                $this->components->info('DB restore was successful!');
            } else {
                $this->components->error('Oops someting went wrong, DB not restored from file '.$filepath);
            }
            Storage::disk('local')->delete('tmp/'.$filename);
        } else {
            $returnVar = COMMAND::FAILURE;
            $this->components->error('The backup file '.$filename.' does NOT exist in folder ');
        }

        return $returnVar;
    }
}
