<?php

namespace App\Jobs;


use App\Models\Region;
use App\Models\League;
use App\Models\Game;
use App\Jobs\GenerateClubGamesReport;
use App\Enums\ReportFileType;
use App\Enums\ReportScope;
use App\Enums\Role;
use App\Notifications\ClubReportsAvailable;

use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessClubReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Region $region;

    /**
     * Create a new job instance.
     *
     * @param  Region  $region
     * @return void
     */
    public function __construct(Region $region)
    {
        // set report scope
        $this->region = $region;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (Storage::exists($this->region->club_folder)) {
            // remove old reports
            Storage::deleteDirectory($this->region->club_folder);
        };
        // and recreate
        Storage::makeDirectory($this->region->club_folder);

        // get all clubs with games
        $clubs = $this->region->clubs()->active()->get();
        $rtypes = $this->region->fmt_club_reports->getFlags();
        // add calendar format as default.
        $rtypes[] = ReportFileType::ICS();
        Log::info('[JOB] kicking off club report batches.', ['region-id' => $this->region->id]);

        foreach ($clubs as $c) {
            // delete old files
            //Storage::delete(File::glob(storage_path().'/app/'.$this->region->club_folder.'/'.$c->shortname.'*'));

            // build list of report jobs based on format
            $rpt_jobs = array();
            foreach ($rtypes as $rtype) {
                if ($rtype->hasFlag(ReportFileType::XLSX) or $rtype->hasFlag(ReportFileType::ODS)) {
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ms_all());
                } elseif ($rtype->hasFlag(ReportFileType::CSV)) {
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_all());
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_home());
                } elseif ($rtype->hasFlag(ReportFileType::PDF) or $rtype->hasFlag(ReportFileType::HTML) or $rtype->hasFlag(ReportFileType::ICS)) {
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_all());
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_home());
                    $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_referee());

                    $leagues = Game::where('club_id_home', $c->id)->with('league')->get()->pluck('league.id')->unique();
                    foreach ($leagues as $l) {
                        $rpt_jobs[] = new GenerateClubGamesReport($this->region, $c, $rtype, ReportScope::ss_club_league(), League::find($l));
                    }
                }
            };

            $note = new ClubReportsAvailable($c);
            $region = $this->region;

            $batch = Bus::batch($rpt_jobs)
                ->then(function (Batch $batch) use ($c, $note, $region) {
                    // All jobs completed successfully...
                    if ($c->memberIsA(Role::ClubLead())) {
                        $clead = $c->members()->wherePivot('role_id', Role::ClubLead)->first();
                        // $clead->notify( $note);
                        Log::info('[NOTIFICATION] club reports available.', ['member-id' => $clead->id]);
                    }
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
                ->name('Club Reports ' . $c->shortname)
                ->onConnection('redis')
                ->onQueue('region_'.$this->region->id)
                ->dispatch();
        }
    }
}
