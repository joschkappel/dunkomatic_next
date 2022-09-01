<?php

namespace App\Http\Controllers;

use App\Jobs\ReportProcessor;
use App\Models\Region;
use App\Enums\Report;
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
    public function run_job(Region $region, int $jobclass)
    {
        $report = Report::coerce($jobclass);
        ReportProcessor::dispatchSync(collect([$report]), collect([$region]));
        return redirect()->back();
    }
}
