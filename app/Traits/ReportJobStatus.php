<?php

namespace App\Traits;

use App\Enums\Report;
use App\Models\Region;
use App\Models\ReportJob;

trait ReportJobStatus
{
    public function job_starting(Region $region, Report $report): ReportJob
    {
        $version = 0;
        // get current  version and increase by 1
        $rj = ReportJob::where('report_id', $report)->where('region_id', $region->id)->first();
        $start_ts = now();

        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['lastrun_at' => $start_ts, 'running' => true, 'version' => $start_ts->format('ymd_His')]
        );

        return $rj;
    }

    private static function job_finished(Region $region, Report $report): ReportJob
    {
        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['running' => false, 'lastrun_ok' => true]
        );

        return $rj;
    }

    private static function job_failed(Region $region, Report $report): ReportJob
    {
        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['running' => false, 'lastrun_ok' => false]
        );

        return $rj;
    }

    public function job_cancelled(Region $region, Report $report): ReportJob
    {
        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['running' => false, 'lastrun_ok' => false]
        );

        return $rj;
    }

    public function job_version(Region $region, Report $report): string
    {
        $rj = ReportJob::where('report_id', $report)->where('region_id', $region->id)->first();

        return $rj->version;
    }

    public function reset_job_version(Region $region, Report $report): ReportJob
    {
        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['version' => null, 'lastrun_at' => null]
        );

        return $rj;
    }
}
