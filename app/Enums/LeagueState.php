<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static Assignment()
 * @method static static Registration()
 * @method static static Selection()
 * @method static static Scheduling()
 * @method static static Freeze()
 * @method static static Live()
 */
final class LeagueState extends Enum implements LocalizedEnum
{
    const Assignment =   0;
    const Registration =   1;
    const Selection =   2;
    const Scheduling = 3;
    const Live = 4;
    const Freeze = 5;
}
