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
use App\Models\Team;
use App\Traits\ReportJobStatus;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ReportJobStatus;

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
        [$impacted_regions, $impacted_leagues, $impacted_clubs, $impacted_contact_regions] = $this->getImpactedModels($this->run_regions, $modified_instances);
        Log::debug('[JOB] REPORT PROCESSOR impacted models', ['region' => $impacted_regions->pluck('code'), 'leagues' => $impacted_leagues->pluck('shortname'), 'clubs' => $impacted_clubs->pluck('shortname')]);

        // default report types
        $def_type = ReportFileType::flags([ReportFileType::HTML()]);

        // loop through all impacted reports types
        foreach ($impacted_reports as $irpt) {
            // collect jobs to run per region
            $rpt_jobs = collect();
            foreach (Region::all() as $r) {
                $rpt_jobs[$r->id] = collect();
                $rpt_jobs[$r->id]['excel'] = collect();
                $rpt_jobs[$r->id]['clubs'] = collect();
                $rpt_jobs[$r->id]['leagues'] = collect();
                $rpt_jobs[$r->id]['regions'] = collect();
            }

            switch ($irpt) {
                case Report::AddressBook():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    // run only for top level region
                    if ($impacted_contact_regions->count() > 0) {
                        $ireg = $impacted_contact_regions->first();
                        if ($ireg->is_base_level) {
                            $ireg = $ireg->parentRegion;
                        }
                        $rtype = $def_type;
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id]['regions'][] = new GenerateRegionContactsReport($ireg, $rtype);
                        $rpt_jobs[$ireg->id]['excel'][] = new GenerateRegionContactsReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::RegionGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_regions as $ireg) {
                        $rtype = $def_type;
                        $rtype->addFlag(ReportFileType::ICS());
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id]['regions'][] = new GenerateRegionGamesReport($ireg, $rtype);
                        $rpt_jobs[$ireg->id]['excel'][] = new GenerateRegionGamesReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::LeagueBook():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_regions as $ireg) {
                        $rtype = $def_type;
                        $rtype->addFlags($ireg->fmt_league_reports->getFlags());
                        $rpt_jobs[$ireg->id]['regions'][] = new GenerateRegionLeaguesReport($ireg, $rtype);
                        $rpt_jobs[$ireg->id]['excel'][] = new GenerateRegionLeaguesReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ', ['rpt' => $irpt->description, 'region' => $ireg->code]);
                    }
                    break;
                case Report::LeagueGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_leagues as $l) {
                        $rtype = $def_type;
                        $rtype->addFlag(ReportFileType::ICS());
                        $rtype->addFlags($l->region->fmt_league_reports->getFlags());
                        $rpt_jobs[$l->region->id]['leagues'][] = new GenerateLeagueGamesReport($l->region, $l, $rtype);
                        $rpt_jobs[$l->region->id]['excel'][] = new GenerateLeagueGamesReport($l->region, $l, ReportFileType::XLSX());
                        Log::info('add jobs for ', ['rpt' => $irpt->description, 'league-id' => $l->id]);
                    }
                    break;
                case Report::ClubGames():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_clubs as $c) {
                        $rtype = $def_type;
                        $rtype->addFlag(ReportFileType::ICS());
                        $rtype->addFlags($c->region->fmt_club_reports->getFlags());
                        $rpt_jobs[$c->region->id]['clubs'][] = new GenerateClubGamesReport($c->region, $c, $rtype);
                        $rpt_jobs[$c->region->id]['excel'][] = new GenerateClubGamesReport($c->region, $c, ReportFileType::XLSX());
                        Log::info('add jobs for ', ['rpt' => $irpt->description, 'club-id' => $c->id]);
                    }
                    break;
                case Report::Teamware():
                    Log::info('checking report ', ['rpt' => $irpt->description]);
                    foreach ($impacted_leagues as $l) {
                        $rpt_jobs[$l->region->id]['leagues'][] = new GenerateTeamwareReport($l);
                        Log::info('add job for ', ['rpt' => $irpt->description, 'league-id' => $l->id]);
                    }
                    foreach ($impacted_regions as $r) {
                        foreach ($r->leagues as $l) {
                            $rpt_jobs[$r->id]['leagues'][] = new GenerateTeamwareReport($l);
                            Log::info('add job for ', ['rpt' => $irpt->description, 'league-id' => $l->id]);
                        }
                    }
                    break;
                default:
                    Log::error('no job added for ', ['rpt' => $irpt->description]);
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
            $contact_regions = $regions;
        } else {
            $games = collect();
            $contact_regions = collect();

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
                $contact_regions = $contact_regions->concat(Game::whereIn('gym_id', $gyms)->pluck('region_id_home'));
                Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by gyms' => $games->count(), 'contacts' => $contact_regions->count()]);
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
                $clubs = Membership::whereIn('id', $mships)->where('membership_type', Club::class)->pluck('membership_id')->unique();
                if ($clubs->count() > 0) {
                    $games = $games->concat(Game::whereIn('club_id_home', $clubs)->orWhereIn('club_id_guest', $clubs)->pluck('id'));
                    $contact_regions = $contact_regions->concat(Club::whereIn('id', $clubs)->pluck('region_id'))->unique();
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by club members' => $games->count(), 'contacts' => $contact_regions->count()]);
                }

                $leagues = Membership::whereIn('id', $mships)->where('membership_type', League::class)->pluck('membership_id')->unique();
                if ($leagues->count() > 0) {
                    $games = $games->concat(Game::whereIn('league_id', $leagues)->pluck('id'));
                    $contact_regions = $contact_regions->concat(League::whereIn('id', $leagues)->pluck('region_id'))->unique();
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by league members' => $games->count(), 'contacts' => $contact_regions->count()]);
                }

                $teams = Membership::whereIn('id', $mships)->where('membership_type', Team::class)->pluck('membership_id')->unique();
                if ($teams->count() > 0) {
                    $games = $games->concat(Game::whereIn('team_id_home', $teams)->orWhereIn('team_id_guest', $teams)->pluck('id'));
                    $contact_regions = $contact_regions->concat(Team::whereIn('id', $teams)->get()->pluck('club.region_id'))->unique();
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by team members' => $games->count(), 'contacts' => $contact_regions->count()]);
                }

                $regions = Membership::whereIn('id', $mships)->where('membership_type', Region::class)->pluck('membership_id')->unique();
                if ($regions->count() > 0) {
                    $games = $games->concat(Game::whereIn('region_id_league', $regions)->pluck('id'));
                    $contact_regions = $contact_regions->concat($regions)->unique();
                    Log::debug('[JOB] REPORT PROCESSOR impacted games', ['by region members' => $games->count(), 'contacts' => $contact_regions->count()]);
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
            // filter only leagues in state scheduling or referee
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
            // get contact regions
            $contact_regions = Region::whereIn('id', $contact_regions)->get();
        }

        Log::debug('found impacts on ', ['regions' => $regions->count(), 'leagues' => $leagues->count(), 'clubs' => $clubs->count(), 'contact_regions' => $contact_regions->count()]);

        return [$regions, $leagues, $clubs, $contact_regions];
    }

    private function dispatchReportBatch(Report $report, Collection $joblist): void
    {
        foreach ($joblist as $rid => $j) {
            if ($j->count() > 0) {
                $region = Region::find($rid);
                foreach ($j as $queue => $joblist) {
                    Log::notice('[JOB] dispatching batch', ['name' => 'Report Generator Jobs ' . $region->code . ' ' . $report->key, 'jobs' => $joblist->count()]);

                    $rj = $this->job_starting($region, $report);
                    $batch = Bus::batch($joblist->toArray())
                    ->then(function (Batch $batch) use ($report, $region) {
                        Log::info('[JOB] finished', ['jobs' => $batch->processedJobs()]);
                        // update region job status
                        static::job_finished($region, $report);
                    })
                        ->finally(function (Batch $batch) use ($report, $region) {
                            if ($batch->failedJobs > 0) {
                                Log::error('[JOB] errored', ['jobs' => $batch->failedJobs]);
                                static::job_failed($region, $report);
                            }
                        })
                        ->name('Report Generator Jobs ' . $region->code . ' ' . $report->key)
                        ->onConnection('redis')
                        ->onQueue($queue)
                        ->dispatch();
                }
            }
        }
    }
}
