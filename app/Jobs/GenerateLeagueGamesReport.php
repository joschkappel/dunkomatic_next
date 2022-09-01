<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Region;
use App\Models\User;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Enums\Report;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\CalendarComposer;
use Illuminate\Support\Facades\Storage;

use App\Exports\LeagueGamesReport;
use App\Models\ReportDownload;
use App\Notifications\LeagueReportsAvailable;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GenerateLeagueGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $export_folder;
    protected string $rpt_name;
    protected Region $region;
    protected ReportScope $scope;
    protected League $league;
    protected ReportFileType $rtype;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, League $league, ReportFileType $rtype)
    {
        // set report scope
        $this->region = $region;
        $this->league = $league;
        $this->rtype = $rtype;

        // make sure folders are there
        $this->export_folder = $region->league_folder;
        $this->rpt_name = $this->export_folder . '/' . $this->league->shortname;
        $this->rpt_name .= '_Rundenplan.';

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

        foreach ($this->rtype->getFlags() as $rtype){
            $rpt_name = $this->rpt_name . $rtype->description;
            $rpt_name = Str::replace(' ','-', $rpt_name );

            Log::info('[JOB][LEAGUE GAMES REPORTS] started.', [
                'region-id' => $this->region->id,
                'league-id' => $this->league->id,
                'format' => $rtype->key,
                'path' => $rpt_name
            ]);

            if ($rtype->hasFlag(ReportFileType::PDF)) {
                Excel::store(new LeagueGamesReport($this->league->id ), $rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
            } elseif ($rtype->hasFlag(ReportFileType::ICS)) {
                // do calendar files
                $calendar = CalendarComposer::createLeagueCalendar($this->league);
                if ($calendar != null) {
                    Storage::put($rpt_name, $calendar->get());
                }
            } else {
                Excel::store(new LeagueGamesReport($this->league->id), $rpt_name);
            }
        }

        // get interested users
        $to_notify = ReportDownload::where('report_id', Report::LeagueGames() )->where('model_class', League::class)->where('model_id', $this->league->id)->pluck('user_id');
        $users = User::whereIn('id', $to_notify)->get();

        if ($users->count()>0){
            // send notification
            Notification::send($users, new LeagueReportsAvailable($this->league));
        }
    }
}
