<?php

namespace App\Jobs;

use App\Enums\LeagueState;
use App\Models\Gym;
use App\Models\Club;
use App\Models\League;
use App\Models\Team;
use App\Models\Region;
use App\Models\Membership;
use App\Models\Member;
use App\Models\ReportClass;
use App\Enums\Report;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Models\ReportJob;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

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
        if ($this->run_rpts->count() > 0){
            $impacted_reports = $this->run_rpts;
            $modified_instances = collect();
            $modified_classes = collect();
            Log::info('[JOB] kicking off process reports job',['impacted_reports'=>$impacted_reports]);
        } else {
            list($modified_instances, $modified_classes, $impacted_reports) = $this->getImpactedReports();
            Log::info('[JOB] kicking off process reports job',['modified_classes'=>$modified_classes, 'impcated_reports'=>$impacted_reports]);
            Log::debug('[JOB] base audits',[$modified_instances]);
        }

        // now get all impacted regions
        if ($this->run_regions->count() > 0){
            $impacted_regions = $this->run_regions;
        } else {
            $impacted_regions = $this->getImpactedRegions($modified_instances);
        }
        Log::debug('[JOB] impacted regions',[$impacted_regions->pluck('code')]);

        // loop thru all impacted regions
        foreach( $impacted_regions as $ireg ){

            // loop through all impacted reports types
            foreach( $impacted_reports as $irpt ){
                // collect jobs to run
                $rpt_jobs = array();
                switch ($irpt) {
                    case Report::AddressBook():
                        if ($ireg->is_top_level){
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg, ReportFileType::HTML());
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg, ReportFileType::XLSX());
                            Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        } else {
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg->parentRegion, ReportFileType::HTML());
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg->parentRegion, ReportFileType::XLSX());
                            Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        }
                        break;
                    case Report::RegionGames():
                        $rpt_jobs[] = new GenerateRegionGamesReport($ireg, ReportFileType::HTML());
                        $rpt_jobs[] = new GenerateRegionGamesReport($ireg, ReportFileType::XLSX());
                        $rpt_jobs[] = new GenerateRegionGamesReport($ireg,  ReportFileType::ICS() );
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::LeagueBook():
                        $rpt_jobs[] = new GenerateRegionLeaguesReport($ireg, ReportFileType::HTML());
                        $rpt_jobs[] = new GenerateRegionLeaguesReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::LeagueGames():
                        $ileague = $this->getImpactedLeagues($ireg, $modified_instances);
                        foreach($ileague as $l){
                            $rpt_jobs[] = new GenerateLeagueGamesReport($ireg, $l, ReportFileType::HTML() );
                            $rpt_jobs[] = new GenerateLeagueGamesReport($ireg, $l, ReportFileType::XLSX() );
                            $rpt_jobs[] = new GenerateLeagueGamesReport($ireg, $l, ReportFileType::ICS() );
                        }
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::ClubGames():
                        $iclub = $this->getImpactedClubs($ireg, $modified_instances);
                        foreach($iclub as $c){
                            $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::XLSX(), ReportScope::ms_all());
                            $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::HTML(), ReportScope::ms_all());
                            $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::ICS(), ReportScope::ss_club_all());
                            $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::ICS(), ReportScope::ss_club_home());
                            $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::ICS(), ReportScope::ss_club_referee());
                            $leagues = Game::where('club_id_home', $c->id)->with('league')->get()->pluck('league.id')->unique();
                            foreach ($leagues as $l) {
                                $rpt_jobs[] = new GenerateClubGamesReport($ireg, $c, ReportFileType::ICS(), ReportScope::ss_club_league(), League::find($l));
                            }
                        }
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::Teamware():
                        $ileague = $this->getImpactedLeagues($ireg, $modified_instances);
                        foreach($ileague as $l){
                            $rpt_jobs[] = new GenerateTeamwareReport( $l );
                        }
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    default:
                        Log::error('no job added for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                }
                if (count($rpt_jobs)>0){
                    $this->dispatchReportBatch($irpt, $ireg, $rpt_jobs);
                }

            }
        }

/*         $audit_item = $modified_instances->first();
        $audit_class = new $audit_item->auditable_type;
        // try to get instance
        $audit_inst = $audit_class::find($audit_item->auditable_id);

        // $gym_id = $modified_instances->firstWhere('auditable_type', Gym::class)->auditable_id;
        // $gym = Gym::find($gym_id);
        Log::debug('[JOB] got this ',[$audit_inst]);
 */
    }



    private function getImpactedReports(): Array
    {
        // get all models that have been modified yesterday
        $mod_instances = Audit::whereDate('created_at', now()->subDays(1))->select('auditable_type', 'auditable_id')->get();
        $mod_classes = $mod_instances->pluck('auditable_type')->unique();
        $impacted_reports = ReportClass::whereIn('report_class', $mod_classes )->pluck('report_id')->unique();

            return array($mod_instances,$mod_classes, $impacted_reports);

    }
    private function getImpactedRegions( Collection $audits): Collection
    {
        $regions = collect();
        // region for clubs:
        $clubs = $audits->where('auditable_type', Club::class)->pluck('auditable_id')->unique();
        if ($clubs->count()>0){
            $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));
        }

        // region for gyms:
        $gyms = $audits->where('auditable_type', Gym::class)->pluck('auditable_id')->unique();
        if ($gyms->count()>0){
            $clubs = Gym::whereIn('id', $gyms)->select('club_id')->get();
            $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));
        }

        // region for teams:
        $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
        if ($teams->count()>0){
            $clubs = Team::whereIn('id', $teams)->select('club_id')->get();
            $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));
        }

        // region for leagues:
        $leagues = $audits->where('auditable_type', League::class)->pluck('auditable_id')->unique();
        if ($leagues->count()>0){
            $regions = $regions->concat( League::whereIn('id', $leagues)->pluck('region_id'));
        }

        // region for memberships and members:
        $mships = $audits->where('auditable_type', Membership::class)->pluck('auditable_id')->unique();
        $members = $audits->where('auditable_type', Member::class)->pluck('auditable_id')->unique();
        if ($members->count()>0){
            $mships = $mships->concat( Membership::whereIn('member_id', $members)->pluck('id')  )->unique();
        }

        if ($mships->count()>0){
            $clubs = Membership::whereIn('id', $mships)->where('membership_type', Club::class)->select('membership_id')->get();
            if ($clubs->count()>0){
                $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));
            }

            $leagues = Membership::whereIn('id', $mships)->where('membership_type', League::class)->select('membership_id')->get();
            if ($leagues->count()>0){
                $regions = $regions->concat( League::whereIn('id', $leagues)->pluck('region_id'));
            }

            $regions = $regions->concat( Membership::whereIn('id', $mships)->where('membership_type', Region::class)->select('membership_id')->get());
        }

        // region for games:
        $games = $audits->where('auditable_type', Game::class)->pluck('auditable_id')->unique();
        if ($games->count()>0){
            $gregions = Game::whereIn('id', $games)->pluck('region');
            $regions = $regions->concat( Region::whereIn('code', $gregions)->pluck('id'));
        }


        // get unique regions
        $regions = Region::whereIn('id',$regions->unique())->get();

        return $regions;
    }
    private function getImpactedLeagues( Region $region, Collection $audits): Collection
    {
        if ($this->run_regions->count() > 0){
            $leagues = $region->leagues->whereIn('state',[LeagueState::Scheduling(), LeagueState::Referees(), LeagueState::Live()]);
        } else {
            $leagues = collect();
            // leagues from league:
            $mod_leagues = $audits->where('auditable_type', League::class)->pluck('auditable_id')->unique();
            if ($mod_leagues->count() > 0){
                $leagues = $leagues->concat( League::whereIn('id', $mod_leagues)->pluck('id'));
            }

            // leagues from teams:
            $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
            if ($teams->count() > 0){
                $leagues = $leagues->concat( Team::whereIn('id', $teams)->pluck('league_id'));
            }

            // leagues from games:
            $games = $audits->where('auditable_type', Game::class)->pluck('auditable_id')->unique();
            if ($games->count() > 0){
                $leagues = $leagues->concat( Game::whereIn('id', $games)->pluck('league_id'));
            }

            // leagues from gyms:
            $gyms = $audits->where('auditable_type', Gym::class)->pluck('auditable_id')->unique();
            if ($gyms->count()>0){
                $leagues = $leagues->concat( Game::whereIn('gym_id', $gyms)->pluck('league_id'));
            }

            // get unique leagues
            $leagues = League::where('region_id', $region->id )->whereIn('id',$leagues->unique())->whereIn('state',[LeagueState::Scheduling(), LeagueState::Referees(), LeagueState::Live()])->get();
        }

        return $leagues;
    }
    private function getImpactedClubs( Region $region, Collection $audits): Collection
    {
        if ($this->run_regions->count() > 0){
            $clubs = $region->clubs()->active()->get();
        } else {
            $clubs = collect();
            // clubs from teams:
            $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
            if ($teams->count() > 0){
                $clubs = $clubs->concat( Team::whereIn('id', $teams)->pluck('club_id'));
            }
            // clubs from games:
            $games = $audits->where('auditable_type', Game::class)->pluck('auditable_id')->unique();
            if ($games->count() > 0){
                $clubs = $clubs->concat( Game::whereIn('id', $games)->pluck('club_id_home'));
                $clubs = $clubs->concat( Game::whereIn('id', $games)->pluck('club_id_guest'));
            }
            // leagues from gyms:
            $gyms = $audits->where('auditable_type', Gym::class)->pluck('auditable_id')->unique();
            if ($gyms->count()>0){
                $clubs = $clubs->concat( Gym::whereIn('id', $gyms)->pluck('club_id'));
            }
            // get unique clubs
            $clubs = Club::where('region_id', $region->id )->whereIn('id',$clubs->unique())->active()->get();

        }
        return $clubs;
    }

    private function dispatchReportBatch($report, $region, $joblist): void
    {
        $batch = Bus::batch([$joblist])
        ->then(function (Batch $batch) use ($region, $report) {
            // update region job status
            $rj = ReportJob::updateOrCreate(
                ['report_id' => $report, 'region_id' => $region->id],
                ['lastrun_at' => now(), 'running' => false]
            );
        })
        ->finally(function (Batch $batch) use ($region, $report){
            if ($batch->failedJobs >  0){
                $lastrun = false;
            } else {
                $lastrun = true;
            }
            $rj = ReportJob::updateOrCreate(
                ['report_id' => $report, 'region_id' => $region->id],
                ['lastrun' => $lastrun]
            );

        })
        ->name('Region-'.$region->code.'-Reports-' . $report->key)
        ->onConnection('redis')
        ->onQueue('region_'.$region->id)
        ->dispatch();
    }
}
