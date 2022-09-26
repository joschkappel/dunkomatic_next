<?php

namespace App\Jobs;

use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Exports\ClubGamesReport;
use App\Helpers\CalendarComposer;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\ReportDownload;
use App\Models\User;
use App\Notifications\ClubReportsAvailable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GenerateClubGamesReport implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $export_folder;

    protected string $rpt_name;

    protected Club $club;

    protected Region $region;

    protected ReportScope $scope;

    protected ReportFileType $rtype;

    protected ?League $league;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Region $region, Club $club, ReportFileType $rtype, League $league = null)
    {
        // set report scope
        $this->region = $region;
        $this->club = $club;
        $this->league = $league;
        $this->rtype = $rtype;

        // make sure folders are there

        if (! Storage::exists($this->region->club_folder)) {
            // clean folder
            Storage::makeDirectory($this->region->club_folder);
        }

        $this->export_folder = $region->club_folder;
        $this->rpt_name = $this->export_folder.'/'.$this->club->shortname;
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
        foreach ($this->rtype->getFlags() as $rtype) {
            if (($rtype->hasFlag(ReportFileType::PDF)) or
                  ($rtype->hasFlag(ReportFileType::CSV))) {
                $rpt_name = $this->rpt_name.'_Vereinsplan.'.$rtype->description;
                $rpt_name = Str::replace(' ', '-', $rpt_name);
                Excel::store(new ClubGamesReport($this->club->id, ReportScope::ss_club_all(), (isset($this->league->id)) ? $this->league->id : null),
                    $rpt_name, null, \Maatwebsite\Excel\Excel::MPDF);
                Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                    'region-id' => $this->region->id,
                    'club-id' => $this->club->id,
                    'league-id' => $this->league->id ?? '',
                    'format' => $rtype->key,
                    'path' => $rpt_name,
                ]);
            } elseif ($rtype->hasFlag(ReportFileType::ICS)) {
                // do calendar files
                $calendar = CalendarComposer::createClubCalendar($this->club);
                if ($calendar != null) {
                    $rpt_name = $this->rpt_name.'_Vereinsplan.'.$rtype->description;
                    $rpt_name = Str::replace(' ', '-', $rpt_name);
                    Storage::put($rpt_name, $calendar->get());
                    Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                        'region-id' => $this->region->id,
                        'club-id' => $this->club->id,
                        'league-id' => $this->league->id ?? '',
                        'format' => $rtype->key,
                        'path' => $rpt_name,
                    ]);
                }

                $calendar = CalendarComposer::createClubHomeCalendar($this->club);
                if ($calendar != null) {
                    $rpt_name = $this->rpt_name.'_Heimspielplan.'.$rtype->description;
                    $rpt_name = Str::replace(' ', '-', $rpt_name);
                    Storage::put($rpt_name, $calendar->get());
                    Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                        'region-id' => $this->region->id,
                        'club-id' => $this->club->id,
                        'league-id' => $this->league->id ?? '',
                        'format' => $rtype->key,
                        'path' => $rpt_name,
                    ]);
                }

                $calendar = CalendarComposer::createClubLeagueCalendar($this->club, $this->league);
                if ($calendar != null) {
                    $rpt_name = $this->rpt_name.'_'.$this->league->shortname.'_Rundenplan.'.$rtype->description;
                    $rpt_name = Str::replace(' ', '-', $rpt_name);
                    Storage::put($rpt_name, $calendar->get());
                    Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                        'region-id' => $this->region->id,
                        'club-id' => $this->club->id,
                        'league-id' => $this->league->id ?? '',
                        'format' => $rtype->key,
                        'path' => $rpt_name,
                    ]);
                }

                $calendar = CalendarComposer::createClubRefereeCalendar($this->club);
                if ($calendar != null) {
                    $rpt_name = $this->rpt_name.'_Schiriplan.'.$rtype->description;
                    $rpt_name = Str::replace(' ', '-', $rpt_name);
                    Storage::put($rpt_name, $calendar->get());
                    Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                        'region-id' => $this->region->id,
                        'club-id' => $this->club->id,
                        'league-id' => $this->league->id ?? '',
                        'format' => $rtype->key,
                        'path' => $rpt_name,
                    ]);
                }
            } else {
                $rpt_name = $this->rpt_name.'_Gesamtplan.'.$rtype->description;
                $rpt_name = Str::replace(' ', '-', $rpt_name);
                Excel::store(new ClubGamesReport($this->club->id, ReportScope::ms_all(), (isset($this->league->id)) ? $this->league->id : null), $rpt_name);
                Log::info('[JOB][CLUB GAMES REPORTS] started.', [
                    'region-id' => $this->region->id,
                    'club-id' => $this->club->id,
                    'league-id' => $this->league->id ?? '',
                    'format' => $rtype->key,
                    'path' => $rpt_name,
                ]);
            }

            // get interested users
            $to_notify = ReportDownload::where('report_id', Report::ClubGames())->where('model_class', Club::class)->where('model_id', $this->club->id)->pluck('user_id');
            $users = User::whereIn('id', $to_notify)->get();

            if ($users->count() > 0) {
                // send notification
                Notification::send($users, new ClubReportsAvailable($this->club));
            }
        }
    }
}
