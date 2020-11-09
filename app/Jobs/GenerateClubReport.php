<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Region;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\ClubGamesExport;
use App\Exports\ClubHomeGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateClubReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $export_folder;
    private $club;
    private $region;
    private $rtype = 'pdf';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, Club $club, $rtype)
    {
        // make sure folders are there
        $this->export_folder = $region->club_folder;
        Storage::makeDirectory($this->export_folder);

        // set report scope
        $this->region = $region;
        $this->club = $club;
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
      Excel::store(new ClubGamesExport($this->club->id), $this->export_folder.'/'.Str::of($this->club->shortname)->replace('/','_').'_games.'.$this->rtype);
      Excel::store(new ClubHomeGamesExport($this->club->id), $this->export_folder.'/'.Str::of($this->club->shortname)->replace('/','_').'_games.'.$this->rtype);
    }
}
