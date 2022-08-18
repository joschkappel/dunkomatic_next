<?php

namespace App\Jobs;

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

use App\Jobs\GenerateRegionContactsReport;
use App\Jobs\GenerateRegionGamesReport;
use App\Jobs\GenerateLeagueGamesReport;
use App\Jobs\GenerateRegionLeaguesReport;

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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        list($modified_instances, $modified_classes, $impacted_reports) = $this->getImpactedReports();
        Log::info('[JOB] kicking off process rports job',['modified_classes'=>$modified_classes, 'impcated_reports'=>$impacted_reports]);
        Log::debug('[JOB] base audits',[$modified_instances]);

        // now get all impacted regions
        $impacted_regions = $this->getImpactedRegions($modified_instances);
        Log::debug('[JOB] impacted regions',[$impacted_regions->pluck('code')]);

        // loop thru all impacted regions
        foreach( $impacted_regions as $ireg ){

            // loop through all impacted reports types
            foreach( $impacted_reports as $irpt ){
                switch ($irpt) {
                    case Report::AddressBook():
                        if ($ireg->is_top_level){
                            $rpt_jobs = array();
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg, ReportFileType::HTML());
                            $rpt_jobs[] = new GenerateRegionContactsReport($ireg, ReportFileType::XLSX());
                            Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                            $this->dispatchRegionBatch($irpt, $ireg, $rpt_jobs);
                        }
                        break;
                    case Report::RegionGames():
                        $rpt_jobs = array();
                        $rpt_jobs[] = new GenerateRegionGamesReport($ireg, ReportFileType::HTML());
                        $rpt_jobs[] = new GenerateRegionGamesReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        $this->dispatchRegionBatch($irpt, $ireg, $rpt_jobs);
                        break;
                    case Report::LeagueBook():
                        $rpt_jobs = array();
                        $rpt_jobs[] = new GenerateRegionLeaguesReport($ireg, ReportFileType::HTML());
                        $rpt_jobs[] = new GenerateRegionLeaguesReport($ireg, ReportFileType::XLSX());
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        $this->dispatchRegionBatch($irpt, $ireg, $rpt_jobs);
                        break;
                    case Report::LeagueGames():
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::ClubGames():
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    case Report::Teamware():
                        Log::notice('add job for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
                    default:
                        Log::error('no job added for ',['rpt'=>$irpt->description, 'region'=>$ireg->code]);
                        break;
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
        //$mod_classes = Audit::whereDate('created_at', now()->subDays(1))->pluck('auditable_type')->unique();
        $mod_instances = Audit::whereDate('created_at', now())->select('auditable_type', 'auditable_id')->get();
        $mod_classes = $mod_instances->pluck('auditable_type')->unique();
        $impacted_reports = ReportClass::whereIn('report_class', $mod_classes )->pluck('report_id')->unique();
        return array($mod_instances,$mod_classes, $impacted_reports);

    }

    private function getImpactedRegions( Collection $audits): Collection
    {
        $regions = collect();
        // region for clubs:
        $clubs = $audits->where('auditable_type', Club::class)->pluck('auditable_id')->unique();
        $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));

        // region for gyms:
        $gyms = $audits->where('auditable_type', Gym::class)->pluck('auditable_id')->unique();
        $clubs = Gym::whereIn('id', $gyms)->select('club_id')->get();
        $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));

        // region for teams:
        $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
        $clubs = Gym::whereIn('id', $teams)->select('club_id')->get();
        $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));

        // region for leagues:
        $leagues = $audits->where('auditable_type', League::class)->pluck('auditable_id')->unique();
        $regions = $regions->concat( League::whereIn('id', $leagues)->pluck('region_id'));

        // region for memberships and members:
        $mships = $audits->where('auditable_type', Membership::class)->pluck('auditable_id')->unique();
        $members = $audits->where('auditable_type', Member::class)->pluck('auditable_id')->unique();
        $mships = $mships->concat( Membership::whereIn('member_id', $members)->pluck('id')  )->unique();

        $clubs = Membership::whereIn('id', $mships)->where('membership_type', Club::class)->select('membership_id')->get();
        $regions = $regions->concat( Club::whereIn('id', $clubs)->pluck('region_id'));

        $leagues = Membership::whereIn('id', $mships)->where('membership_type', League::class)->select('membership_id')->get();
        $regions = $regions->concat( League::whereIn('id', $leagues)->pluck('region_id'));

        $regions = $regions->concat( Membership::whereIn('id', $mships)->where('membership_type', Region::class)->select('membership_id')->get());

        // get unique regions
        $regions = Region::whereIn('id',$regions->unique())->get();

        return $regions;
    }
    private function getImpactedLeagues( Region $region, Collection $audits): Collection
    {
        $leagues = collect();
        // region for leagues:
        $mod_leagues = $audits->where('auditable_type', League::class)->pluck('auditable_id')->unique();
        $leagues = $leagues->concat( League::whereIn('id', $mod_leagues)->where('region_id',$region->id)->pluck('id'));

        // region for teams:
        $teams = $audits->where('auditable_type', Team::class)->pluck('auditable_id')->unique();
        $leagues = $leagues->concat( Team::whereIn('id', $teams)->with('club')->get()->groupBy('club.region.id')[$region->id]->pluck('league_id'));



        // get unique leagues
        $leagues = League::whereIn('id',$leagues->unique())->get();

        return $leagues;
    }

    private function dispatchRegionBatch($report, $region, $joblist): void
    {
        $batch = Bus::batch($joblist)
        ->then(function (Batch $batch) use ($region) {
            // update region
            $region->update([
                'job_league_reports_running' => false,
                'job_league_reports_lastrun_at' => now()
            ]);
        })
        ->finally(function (Batch $batch) use ($region){
            if ($batch->failedJobs >  0){
                $region->update(['job_league_reports_lastrun_ok' => false]);
            } else {
                $region->update(['job_league_reports_lastrun_ok' => true]);
            }
        })
        ->name('Region-'.$region->code.'-Reports-' . $report->key)
        ->onConnection('redis')
        ->onQueue('region_'.$region->id)
        ->dispatch();
    }
    private function dispatchLeagueBatch($report, $region, $joblist): void
    {
        $batch = Bus::batch($joblist)
        ->then(function (Batch $batch) use ($region) {
            // update region
            $region->update([
                'job_league_reports_running' => false,
                'job_league_reports_lastrun_at' => now()
            ]);
        })
        ->finally(function (Batch $batch) use ($region){
            if ($batch->failedJobs >  0){
                $region->update(['job_league_reports_lastrun_ok' => false]);
            } else {
                $region->update(['job_league_reports_lastrun_ok' => true]);
            }
        })
        ->name('Region-'.$region->code.'-Reports-' . $report->key)
        ->onConnection('redis')
        ->onQueue('region_'.$region->id)
        ->dispatch();
    }
    private function dispatchClubBatch($report, $region, $joblist): void
    {
        $batch = Bus::batch($joblist)
        ->then(function (Batch $batch) use ($region) {
            // update region
            $region->update([
                'job_club_reports_running' => false,
                'job_club_reports_lastrun_at' => now()
            ]);
        })
        ->finally(function (Batch $batch) use ($region){
            if ($batch->failedJobs >  0){
                $region->update(['job_club_reports_lastrun_ok' => false]);
            } else {
                $region->update(['job_club_reports_lastrun_ok' => true]);
            }
        })
        ->name('Region-'.$region->code.'-Reports-' . $report->key)
        ->onConnection('redis')
        ->onQueue('region_'.$region->id)
        ->dispatch();
    }
}
