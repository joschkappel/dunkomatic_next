<?php

namespace App\Jobs;

use App\League;
use App\Region;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\LeagueGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessLeagueReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $export_folder;
    private $regions = array();

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($region_code)
    {
        // make sure folders are there
        $this->export_folder = 'exports/'.Str::of(config('global.season'))->replace('/','_').'/'.config('dunkomatic.report_folder_leagues');
        Storage::makeDirectory($this->export_folder);

        // set report scope
        $region = Region::where('code',$region_code)->first();
        $this->regions[] = $region->code;
        $this->regions[] = $region->hq;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get all leagues with games
        $leagues = League::whereIn('region', $this->regions)->get();
        foreach ($leagues as $l){
            Excel::store(new LeagueGamesExport($l->id), $this->export_folder.'/'.Str::of($l->shortname)->replace('/','_').'_games.xlsx');
            Excel::store(new LeagueGamesExport($l->id), $this->export_folder.'/'.Str::of($l->shortname)->replace('/','_').'_games.html');
            Excel::store(new LeagueGamesExport($l->id), $this->export_folder.'/'.Str::of($l->shortname)->replace('/','_').'_games.pdf');
        }
    }
}
