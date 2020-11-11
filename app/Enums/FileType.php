<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\FlaggedEnum;
use Illuminate\Support\Str;

/**
 * @method static static PDF()
 * @method static static HTML()
 * @method static static XLSX()
 * @method static static ODS()
 * @method static static CSV()
 * @method static static TSV()
 */
final class FileType extends FlaggedEnum
{
    const PDF =    1 << 0;
    const HTML =    1 << 1;
    const XLSX =    1 << 2;
    const ODS =     1 << 3;
    const CSV =     1 << 4;
    const TSV =     1 << 5;

    public static function getDescription($value): string
    {
        return Str::lower(self::getKey($value));
    }
}
