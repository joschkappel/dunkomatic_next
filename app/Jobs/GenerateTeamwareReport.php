<?php

namespace App\Jobs;

use App\Enums\Report;
use App\Exports\TeamwareGamesExport;
use App\Exports\TeamwareTeamsExport;
use App\Models\League;
use App\Traits\ReportManager;
use App\Traits\ReportJobStatus;
use App\Traits\ReportVersioning;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GenerateTeamwareReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ReportJobStatus, ReportManager, ReportVersioning;

    protected string $export_folder;

    protected League $league;

    protected string $tw_teams;

    protected string $tw_games;

    /**
     * Create a new job instance.
     *
     * @param  League  $league
     * @return void
     */
    public function __construct(League $league)
    {
        // set report scope
        $this->league = $league->load('region');

        // make sure folders are there
        if (! Storage::exists($this->league->region->teamware_folder)) {
            // clean folder
            Storage::makeDirectory($this->league->region->teamware_folder);
        }
        $this->export_folder = $this->league->region->teamware_folder;

        // teamware filenames
        $this->tw_teams = $this->export_folder.'/'.Str::slug($this->league->shortname, '_').'_teams';
        $this->tw_games = $this->export_folder.'/'.Str::slug($this->league->shortname, '_').'_games';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch() !== null) {
            if ($this->batch()->cancelled()) {
                // Detected cancelled batch...
                return;
            }
        }
        $version = $this->get_report_version($this->league->region, Report::Teamware());

        $tw_games = $this->tw_games.'_v'.$version.'.csv';
        $tw_teams = $this->tw_teams.'_v'.$version.'.csv';

        // move previous versions
        $this->move_old_report($this->league->region, $this->export_folder, Str::slug($this->league->shortname, '_'));

        Log::info('[JOB][TEAMWARE REPORTS] started.', [
            'league-id' => $this->league->id,
            'teams path' => $tw_teams,
            'games path' => $tw_games, ]);

        Excel::store(new TeamwareTeamsExport($this->league), $tw_teams, null, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new TeamwareGamesExport($this->league), $tw_games, null, \Maatwebsite\Excel\Excel::CSV);
    }
}
