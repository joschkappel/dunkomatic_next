<?php

namespace App\Http\Controllers;

use App\Enums\Report;
use App\Jobs\ReportProcessor;
use App\Models\Region;
use App\Traits\ReportVersioning;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobController extends Controller
{
    use ReportVersioning;

    /**
     * run a job
     *
     * @return Back
     */
    public function run_job(Region $region, int $jobclass)
    {
        $report = Report::coerce($jobclass);
        ReportProcessor::dispatchSync(collect([$report]), collect([$region]));

        return true;
    }

    /**
     * remove reports
     *
     * @return Back
     */
    public function remove_reports(Region $region, int $jobclass)
    {
        $report = Report::coerce($jobclass);
        Log::info('Remove report files with type', ['region-id' => $region->id, 'report' => $report->key]);

        switch ($jobclass) {
            case Report::AddressBook:
                $rpt_name = $region->region_folder.'/'.$region->code.'_'.Report::coerce($jobclass)->getReportFilename();
                // delete files
                $this->delete_report_files($region, $report, $rpt_name);
                break;
            case Report::RegionGames:
                $rpt_name = $region->region_folder.'/'.$region->code.'_'.Report::coerce($jobclass)->getReportFilename();
                // delete file
                $this->delete_report_files($region, $report, $rpt_name);
                break;
            case Report::LeagueBook:
                $rpt_name = $region->region_folder.'/'.$region->code.'_'.Report::coerce($jobclass)->getReportFilename();
                // delete file
                $this->delete_report_files($region, $report, $rpt_name);
                break;
            case Report::Teamware:
                $folder = $region->teamware_folder;
                // delete file
                $this->clean_report_folder($region, $report, $folder);
                break;
            case Report::LeagueGames:
                $folder = $region->league_folder;
                // delete file
                $this->clean_report_folder($region, $report, $folder);
                break;
            case Report::ClubGames:
                $folder = $region->club_folder;
                // delete file
                $this->clean_report_folder($region, $report, $folder);
                break;
            default:
                // code...
                break;
        }

        return true;
    }

    private function delete_report_files(Region $region, Report $report, string $rpt_name)
    {
        foreach ($region->filenames() as $rpt) {
            if (Str::startsWith($rpt, $rpt_name)) {
                $res = Storage::delete($rpt);
                Log::notice('File deleted', ['name' => $rpt, 'result' => $res]);
            }
        }
        $this->reset_report_version($region, $report);
    }

    private function clean_report_folder(Region $region, Report $report, string $dir_name)
    {
        foreach (Storage::files($dir_name) as $rpt) {
            $res = Storage::delete($rpt);
            Log::notice('File deleted', ['name' => $rpt, 'result' => $res]);
        }

        $this->reset_report_version($region, $report);
    }
}
