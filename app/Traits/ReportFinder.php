<?php

namespace App\Traits;

use App\Enums\ReportFileType;
use App\Models\Region;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ReportFinder
{
    public function get_reports(string $folder, string $namepart = null, ReportFileType $format): Collection
    {
        $reports = collect(Storage::files($folder))->filter(function ($value, $key) use ($namepart, $format) {
            $fname = Str::of($value)->basename();
            if ($format->value == ReportFileType::None) {
                if ($namepart != null) {
                    return Str::contains($fname, $namepart);
                } else {
                    return true;
                }
            } else {
                if ($namepart != null) {
                    return Str::contains($fname, $namepart) and Str::contains($fname, '.'.Str::lower($format->key));
                } else {
                    return Str::contains($fname, '.'.Str::lower($format->key));
                }
            }
        });

        return $reports;
    }

    public function move_old_report(Region $region, string $folder, string $namepart): void
    {
        // find all reports for this version
        Log::info('finding reports', ['folder' => $folder, 'namepart' => $namepart]);
        $reports = collect(Storage::files($folder))->filter(function ($value, $key) use ($namepart) {
            $fname = Str::of($value)->basename();

            return Str::contains($fname, $namepart);
        });

        if ($reports->count() > 0) {
            Log::debug('found reports to move', ['reports' => $reports]);

            // check that archive exists
            $archive = $region->archive_folder;
            if (! Storage::exists($archive)) {
                // clean folder
                Storage::makeDirectory($archive);
            }

            // now move all founc reports to the archive
            foreach ($reports as $rpt) {
                Storage::move($rpt, $archive.'/'.Str::of($rpt)->basename());
                Log::notice('report moved', ['from' => $rpt, 'to' => $archive.'/'.Str::of($rpt)->basename()]);
            }
        } else {
            Log::notice('no reports found');
        }
    }
}
