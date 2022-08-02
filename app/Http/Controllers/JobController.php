<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessClubReports;
use App\Jobs\ProcessLeagueReports;
use App\Jobs\ProcessRegionReport;
use App\Models\Region;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * run a job
     *
     * @return Back
     *
     */
    public function run_job(Region $region, string $jobclass)
    {
        if ($jobclass == 'league_reports'){
            Log::notice('League Report Job Request',['region-id'=>$region->id]);
            ProcessLeagueReports::dispatchSync($region);
            ProcessRegionReport::dispatch($region);
            $region->update(['job_league_reports_running'=>true]);
        } elseif ($jobclass == 'club_reports'){
            Log::notice('Club Report Job Request',['region-id'=>$region->id]);
            ProcessClubReports::dispatchSync($region);
            $region->update(['job_club_reports_running'=>true]);
        } else {
            Log::warning('Unknown Job Request',['region-id'=>$region->id, 'jobclass'=>$jobclass]);
        }
        return redirect()->back();
    }
}
