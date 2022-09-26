<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Male()
 * @method static static Female()
 * @method static static Mixed()
 */
final class LeagueGenderType extends Enum implements LocalizedEnum
{
    const Male = 0;

    const Female = 1;

    const Mixed = 2;
}
