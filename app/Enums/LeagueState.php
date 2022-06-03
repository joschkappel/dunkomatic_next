<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static Setup()
 * @method static static Registration()
 * @method static static Selection()
 * @method static static Scheduling()
 * @method static static Freeze()
 * @method static static Referees()
 * @method static static Live()
 */
final class LeagueState extends Enum implements LocalizedEnum
{
    const Setup =   0;
    const Registration =  1;
    const Selection =   3;
    const Freeze = 4;
    const Scheduling = 5;
    const Referees = 6;
    const Live = 7;

}
