<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

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
    const Setup = 0;

    const Registration = 1;

    const Selection = 3;

    const Freeze = 4;

    const Scheduling = 5;

    const Referees = 6;

    const Live = 7;

    public function getIcon()
    {
        switch ($this->value) {
            case 0:
                return '<i class="fas fa-cog"></i>';
                break;
            case 1:
                return '<i class="fas fa-file-signature"></i>';
                break;
            case 3:
                return '<i class="fas fa-list-ol"></i>';
                break;
            case 4:
                return '<i class="fas fa-pause-circle"></i>';
                break;
            case 5:
                return '<i class="fas fa-calendar-alt"></i>';
                break;
            case 6:
                return '<i class="fas fa-stopwatch"></i>';
                break;
            case 7:
                return '<i class="fas fa-fire"></i>';
                break;
            default:
                return '<i class="fas fa-question"></i>';
                break;
        }
    }
}
