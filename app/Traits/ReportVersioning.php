<?php

namespace App\Traits;

use App\Enums\Report;
use App\Models\Club;
use App\Models\League;
use App\Models\Region;
use App\Models\ReportJob;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait ReportVersioning
{
    public function new_report_version(DateTime $version_date): string
    {
        return $version_date->format('ymd_His') ?? '000000_000000';
    }

    public function get_report_version(Region $region, Report $report): string
    {
        $rj = ReportJob::where('report_id', $report)->where('region_id', $region->id)->first();

        return $rj->version ?? '';
    }

    public function reset_report_version(Region $region, Report $report): ReportJob
    {
        $rj = ReportJob::updateOrCreate(
            ['report_id' => $report, 'region_id' => $region->id],
            ['version' => null, 'lastrun_at' => null]
        );

        return $rj;
    }

    public function get_outdated_downloads(User $user): Collection
    {
        // get all downloads for the user
        $reports = $user->report_downloads;

        if ($reports->count() > 0) {
            // now loop thru the donloads and check for newer versions
            $outdated = collect();
            foreach ($reports as $rd) {
                // first get region
                if ($rd->model_class == Club::class) {
                    $region = Club::find($rd->model_id)->region;
                } elseif ($rd->model_class == League::class) {
                    $region = League::find($rd->model_id)->region;
                } else {
                    $region = Region::find($rd->model_id);
                }
                // now get version
                $rj = ReportJob::where('report_id', $rd->report_id)
                                ->where('region_id', $region->id)->first();

                // check if rj-version is newer than rd-version
                $download_version = $this->get_version_date($rd->version ?? '000000_000000');
                $latest_version = $this->get_version_date($rj->version ?? '000000_000000');

                if ($latest_version->greaterThan($download_version)) {
                    $outdated->push($rd);
                }
            }
            Log::debug($outdated);

            return $outdated;
        } else {
            return collect();
        }
    }

    private function get_version_date(string $version)
    {
        if (Carbon::canBeCreatedFromFormat($version, 'ymd_His')) {
            $version_date = Carbon::createFromFormat('ymd_His', $version);
        } else {
            $version_date = now();
        }

        return $version_date;
    }
}
