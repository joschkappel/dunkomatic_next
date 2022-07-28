<?php

namespace App\Traits;

use App\Enums\ReportFileType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


trait ReportFinder
{

    public function get_reports( string $folder, string $namepart, ReportFileType $format ): Collection
    {
        $reports = collect(Storage::allFiles($folder))->filter(function ($value, $key) use ($namepart, $format) {
            if ($format->value == ReportFileType::None){
                return Str::contains($value, $namepart);
            } else {
                return Str::contains($value, $namepart) and Str::contains($value, '.'.Str::lower($format->key) );
            };
        });
        return $reports;
    }

}
