<?php

namespace App\Traits;

use App\Enums\ReportFileType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


trait ReportFinder
{

    public function get_reports( string $folder, string $namepart=null, ReportFileType $format ): Collection
    {
        $reports = collect(Storage::allFiles($folder))->filter(function ($value, $key) use ($namepart, $format) {
            $fname = Str::of($value)->basename();
            if ($format->value == ReportFileType::None){
                if ($namepart != null){
                    return Str::contains($fname, $namepart);
                } else {
                    return true;
                }
            } else {
                if ($namepart != null){
                    return Str::contains($fname, $namepart) and Str::contains($fname, '.'.Str::lower($format->key) );
                } else {
                    return Str::contains($fname, '.'.Str::lower($format->key) );
                }
            };
        });
        return $reports;
    }

}
