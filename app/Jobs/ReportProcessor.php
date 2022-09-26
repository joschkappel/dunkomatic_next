<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Models\Club;
use App\Models\Game;
use App\Models\Gym;
use App\Models\League;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Region;
use App\Models\ReportClass;
use App\Models\ReportJob;
use App\Models\Team;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

class ReportProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Collection $run_rpts;

    protected Collection $run_regions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $run_rpts, Collection $run_regions)
    {
        $this->run_regions = $run_regions;
        $this->run_rpts = $run_rpts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get list of reports to run
        if ($this->run_rpts->count() > 0) {
            $impacted_reports = $this->run_rpts;
            $modified_instances = collect();
            $modified_classes = collect();
        } else {
            [$modified_instances, $modified_classes, $impacted_reports] = $this->getImpactedReports();
        }
        Log::info('[JOB] REPORT PROCESSOR kicking off process reports job', ['modified_classes' => $modified_classes, 'impcated_reports' => $impacted_reports, 'base_audits' => $modified_instances]);

        // now get all impacted instances
        [$impacted_regions, $impacted_leagues, $impacted_clubs] = $this->getImpactedModels($this->run_regions, $modified_instances);
        Log::debug('[JOB] REPORT PROCESSOR impacted models', ['region' => $impacted_regions->pluck('code'), 'leagues' => $impacted_leagues->pluck('shortname'), 'clubs' => $impacted_clubs->pluck('shortname')]);

        // loop through all impacted reports types
        foreach ($impacted_reports as $irpt) {
            // collect jobs to run per region
            $rpt_jobs = collect();
            foreach (Region::all() as $r) {
                $rpt_jobs[$r->id] = collect();
            }

            switch ($irpt) {
                case Report::AddressBook():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    // run only for top level region
                    if ($impacted_regions->count() > 0) {
                        $ireg = $impacted_regions->first();
                        if ($ireg->is_base_level) {
                            $ireg = $ireg->parentRegion;
                        }
                        $rtype = ReportFileType::flags(ReportFileType::HTML(), ReportFileType::XLSX());
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id][] = new GenerateRegionContactsReport($ireg, $rtype);
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::RegionGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_regions as $ireg) {
                        $rtype = ReportFileType::flags(ReportFileType::HTML(), ReportFileType::XLSX(), ReportFileType::ICS());
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id][] = new GenerateRegionGamesReport($ireg, $rtype);
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::LeagueBook():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_regions as $ireg) {
                        $rtype = ReportFileType::flags(ReportFileType::HTML(), ReportFileType::XLSX());
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id][] = new GenerateRegionLeaguesReport($ireg, $rtype);
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::LeagueGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_leagues as $l) {
                        $rtype = ReportFileType::flags(ReportFileType::HTML(), ReportFileType::XLSX(), ReportFileType::ICS());
                        $rtype->addFlags($l->region->fmt_league_reports->getFlags());
                        $rpt_jobs[$l->region->id][] = new GenerateLeagueGamesReport($l->region, $l, $rtype);
                        Log::info('add jobs for ', ['rpt' => $irpt->description, 'league-id' => $l->id]);
                    }
                    break;
                case Report::ClubGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_clubs as $c) {
                        $rtype = ReportFileType::flags(ReportFileType::HTML(), ReportFileType::XLSX(), ReportFileType::ICS());
                        $rtype->addFlags($c->region->fmt_club_reports->getFlags());
                        $rpt_jobs[$c->region->id][] = new GenerateClubGamesReport($c->region, $c, $rtype);
                        Log::info('add jobs for ', ['rpt' => $irpt->description, 'club-id' => $c->id]);
                    }
                    break;
                case Report::Teamware():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_leagues as $l) {
                        $rpt_jobs[$l->region->id][] = new GenerateTeamwareReport($l);
                        Log::info('add job for ', ['rpt' => $irpt->description, 'league-id' => $l->id]);
                    }
                    break;
                default:
                    Log::error('no job added for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    break;
            }
            $this->dispatchReportBatch($irpt, $rpt_jobs);
        }
    }

    private function getImpactedReports(): array
    {
        // get all models that have been modified yesterday
        $mod_instances = Audit::whereDate('created_at', now()->subDays(1))->select('auditable_type', 'auditable_id')->get();
        $mod_classes = $mod_instances->pluck('auditable_type')->unique();
        $impacted_reports = ReportClass::whereIn('report_class', $mod_classes)->pluck('report_id')->unique();

        return [$mod_instances, $mod_classes, $impacted_reports];
    }

    private function getImpactedModels(Collection $regions, Collection $audits): array
    {
        if ($regions->count() > 0) {
            $leagues = League::whereIn('region_id', $regions->pluck('id'))->whereIn('state', [LeagueState::Scheduling(), LeagueState::Referees()])->get();
            $clubs = Club::whereIn('region_id', $regions->pluck('id'))->active()->get();
        } else {
            $games = collect();
            // games for clubs:
            $clubs = $audits->where('auditable_type', Club::class)->pluck('auditable_id')->unique();
            if ($clubs->count() > 0) {
                $games = $games->concat(Game::whereIn('club_id_home', $clubs)->orWhereIn('club_id_guest', $clubs)->pluck('id'));
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by club' => $games->count()]);
            }

            // games for gyms:
            $gyms = $audits->where('auditable_type', Gym::class)->pluck('auditable_id')->unique();
            if ($gyms->count() > 0) {
                $games = $games->concat(Game::whereIn('gym_id', $gyms)->pluck('id'));
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by gyms' => $games->count()]);
            }

            // games for teams:
            $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
            if ($teams->count() > 0) {
                $games = $games->concat(Game::whereIn('team_id_home', $teams)->orWhereIn('team_id_guest', $teams)->pluck('id'));
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by teams' => $games->count()]);
            }

            // games for leagues:
            $leagues = $audits->where('auditable_type', League::class)->pluck('auditable_id')->unique();
            $leagues = League::whereIn('id', $leagues)->pluck('id');
            if ($leagues->count() > 0) {
                $games = $games->concat(Game::whereIn('league_id', $leagues)->pluck('id'));
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by leagues' => $games->count()]);
            }

            // games for memberships and members:
            $mships = $audits->where('auditable_type', Membership::class)->pluck('auditable_id')->unique();
            $members = $audits->where('auditable_type', Member::class)->pluck('auditable_id')->unique();
            if ($members->count() > 0) {
                $mships = $mships->concat(Membership::whereIn('member_id', $members)->pluck('id'))->unique();
            }

            if ($mships->count() > 0) {
                $clubs = Membership::whereIn('id', $mships)->where('membership_type', Club::class)->select('membership_id')->get();
                if ($clubs->count() > 0) {
                    $games = $games->concat(Game::whereIn('club_id_home', $clubs)->orWhereIn('club_id_guest', $clubs)->pluck('id'));
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by club members' => $games->count()]);
                }

                $leagues = Membership::whereIn('id', $mships)->where('membership_type', League::class)->select('membership_id')->get();
                $leagues = League::whereIn('id', $leagues)->pluck('id');
                if ($leagues->count() > 0) {
                    $games = $games->concat(Game::whereIn('league_id', $leagues)->pluck('id'));
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by league members' => $games->count()]);
                }

                $regions = Membership::whereIn('id', $mships)->where('membership_type', Region::class)->select('membership_id')->get();
                $regions = Region::whereIn('id', $regions)->pluck('code');
                if ($regions->count() > 0) {
                    $games = $games->concat(Game::whereIn('region', $regions)->pluck('id'));
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by region members' => $games->count()]);
                }
            }

            // games:
            $audit_games = $audits->where('auditable_type', Game::class)->pluck('auditable_id')->unique();
            if ($audit_games->count() > 0) {
                $games = $games->concat($audit_games);
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by games' => $games->count()]);
            }

            // get all impacted games (only for leagues that are in scheduling or referees)
            $all_games = Game::whereIn('id', $games)->get();
            Log::debug('[JOB] REPORT PROCESSOR all impacted games', ['cnt' => $all_games->count()]);

            // get impacted leagues
            $leagues = $all_games->pluck('league_id')->unique();
            // ilter only leagues in state scheduling or referee
            $leagues = League::whereIn('id', $leagues)->whereIn('state', [LeagueState::Scheduling, LeagueState::Referees])->pluck('id');
            // filter games
            $filtered_games = $all_games->whereIn('league_id', $leagues);
            Log::debug('[JOB] REPORT PROCESSOR filtered impacted games', ['cnt' => $filtered_games->count()]);

            // get regions
            $regions = Region::whereIn('id', $filtered_games->pluck('region_id_league'))->orWhereIn('id', $filtered_games->pluck('region_home_id'))->get();
            // get unique leagues from games
            $leagues = League::whereIn('id', $filtered_games->pluck('league_id'))->get();
            // get unique leagues from games
            $clubs = Club::whereIn('id', $filtered_games->pluck('club_id_home'))->get();
        }

        Log::debug('found impacts on ', ['regions' => $regions->count(), 'leagues' => $leagues->count(), 'clubs' => $clubs->count()]);

        return [$regions, $leagues, $clubs];
    }

    private function dispatchReportBatch(Report $report, Collection $joblist): void
    {
        foreach ($joblist as $rid => $j) {
            if ($j->count() > 0) {
                Log::notice('[JOB] diaptching batch', ['name' => 'Report Generator Jobs '.Region::find($rid)->code.' '.$report->key, 'jobs' => $j->count()]);
                $batch = Bus::batch($j->toArray())
                    ->then(function (Batch $batch) use ($report, $rid) {
                        Log::info('[JOB] finished', ['jobs' => $batch->processedJobs()]);
                        // update region job status
                        $rj = ReportJob::updateOrCreate(
                            ['report_id' => $report, 'region_id' => $rid],
                            ['lastrun_at' => now(), 'running' => false]
                        );
                    })
                    ->finally(function (Batch $batch) use ($report, $rid) {
                        if ($batch->failedJobs > 0) {
                            $lastrun = false;
                        } else {
                            $lastrun = true;
                        }
                        $rj = ReportJob::updateOrCreate(
                            ['report_id' => $report, 'region_id' => $rid],
                            ['lastrun' => $lastrun]
                        );
                    })
                    ->name('Report Generator Jobs '.Region::find($rid)->code.' '.$report->key)
                    ->onConnection('redis')
                    ->onQueue('region_'.$rid)
                    ->dispatch();
            }
        }
    }
}
