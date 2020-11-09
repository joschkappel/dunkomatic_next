<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\LeagueGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLeagueReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $export_folder;
    private $region;
    private $rtype = 'pdf';
    private $league;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, League $league, $rtype)
    {
        // make sure folders are there
        $this->export_folder = $region->league_folder;
        Storage::makeDirectory($this->export_folder);

        // set report scope
        $this->region = $region;
        $this->league = $league;
        $this->rtype = $rtype;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      if ($this->batch()->cancelled()) {
        // Detected cancelled batch...

        return;
      }

      Excel::store(new LeagueGamesExport($this->league->id), $this->export_folder.'/'.Str::of($this->league->shortname)->replace('/','_').'_games.'.$this->rtype);
    }
}
