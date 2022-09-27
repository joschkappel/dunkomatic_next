<?php

namespace App\Enums;

use BenSampo\Enum\FlaggedEnum;
use Illuminate\Support\Str;

/**
 * @method static static PDF()
 * @method static static HTML()
 * @method static static XLSX()
 //* @method static static XLS()  - not supported anymor has bug when creating team game sheets
 * @method static static ODS()
 * @method static static CSV()
 * @method static static ICS()
 */
final class ReportFileType extends FlaggedEnum
{
    const PDF = 1 << 0;

    const HTML = 1 << 1;

    const XLSX = 1 << 2;

//    const XLS =     1 << 3;
    const ODS = 1 << 4;

    const CSV = 1 << 5;

    const ICS = 1 << 6;

    public static function getDescription($value): string
    {
        return Str::lower(self::getKey($value));
    }
}
