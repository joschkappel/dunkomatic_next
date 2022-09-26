<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Senior()
 * @method static static Junior()
 * @method static static Mini()
 */
final class LeagueAgeType extends Enum implements LocalizedEnum
{
    const Senior = 0;

    const Junior = 1;

    const Mini = 2;
}
