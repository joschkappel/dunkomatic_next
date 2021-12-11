<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Enums\ReportFileType;

use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\CalendarComposer;
use Illuminate\Support\Facades\Storage;

use App\Exports\LeagueGamesExport;
use App\Exports\TeamwareTeamsExport;
use App\Exports\TeamwareGamesExport;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;

class GenerateTeamwareReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $export_folder;
    protected $league;
    protected $tw_teams;
    protected $tw_games;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(League $league)
    {
        // set report scope
        $this->league = $league;

        // make sure folders are there
        $this->export_folder = $league->region->teamware_folder;

        // teamware filenames
        $this->tw_teams = $this->export_folder . '/' . $this->league->shortname . '_Teams.csv';
        $this->tw_games = $this->export_folder . '/' . $this->league->shortname . '_Games.csv';
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

        Log::info('[JOB][TEAMWARE REPORTS] started.', [
            'league-id' => $this->league->id]);

        Excel::store(new TeamwareTeamsExport($this->league ), $this->tw_teams, NULL, \Maatwebsite\Excel\Excel::CSV);
        Excel::store(new TeamwareGamesExport($this->league ), $this->tw_games, NULL, \Maatwebsite\Excel\Excel::CSV);

    }
}
