<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use Illuminate\Support\Str;

/**
 * @method static static ms_all()
 * @method static static ss_club_all()
 * @method static static ss_club_home()
 * @method static static ss_club_league()
 * @method static static ss_club_referee()
 */

final class ReportScope extends Enum
{
    const ms_all = 1; // multi sheet, all lists
    const ss_club_all = 2; // single sheet, all club games
    const ss_club_home = 3; // single sheet, club home games
    const ss_club_league = 4; // single sheet, club league games
    const ss_club_referee = 5; // single sheet, club referee games

}
